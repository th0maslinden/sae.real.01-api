ARG PHP_VERSION=8.2
ARG NGINX_VERSION=1.27.3
# ETAPE 1 
FROM php:${PHP_VERSION}-fpm-alpine AS contacts_php
#ETAPE 2 
# persistent / runtime deps
RUN apk add --no-cache \
        acl \
        fcgi \
        file \
        gettext \
    ;
# ETAPE 3
ARG APCU_VERSION=5.1.21
RUN set -eux; \
  apk add --no-cache --virtual .build-deps \
      $PHPIZE_DEPS \
      icu-dev \
      libzip-dev \
  ; \
  \
  docker-php-ext-configure zip; \
  docker-php-ext-install -j$(nproc) \
      intl \
      pdo_mysql \
      zip \
  ; \
  pecl install \
      apcu-${APCU_VERSION} \
  ; \
  pecl clear-cache; \
  docker-php-ext-enable \
      apcu \
      opcache \
  ; \
  \
  runDeps="$( \
      scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
          | tr ',' '\n' \
          | sort -u \
          | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
  )"; \
  apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
  \
  apk del .build-deps

# ETAPE 4
COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV PATH="${PATH}:/root/.composer/vendor/bin"

# ETAPE 5
RUN ln -s $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
# ETAPE 6
COPY docker/php/conf.d/config.ini $PHP_INI_DIR/conf.d/config.ini

ENV COMPOSER_ALLOW_SUPERUSER=1
# ETAPE 7
RUN set -eux; \
composer global config --no-plugins allow-plugins.symfony/flex true; \
composer global require "symfony/flex" --prefer-dist --no-progress --classmap-authoritative; \
composer clear-cache
# ETAPE 8
WORKDIR /symfony-contacts
# ETAPE 9 
COPY docker/php/docker-entrypoint.sh /usr/local/bin
# ETAPE 10
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]

# ETAPE 1
FROM nginx:${NGINX_VERSION}-alpine AS contacts_nginx

# ETAPE 2
COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

# ETAPE 3
WORKDIR /symfony-contacts/public

# Etape
FROM contacts_php AS contacts_php_prod
ENV APP_ENV=prod

# ETAPE 1
COPY composer.json composer.lock symfony.lock ./

# ETAPE 2
RUN set -eux; \
    composer install --prefer-dist --no-dev --no-scripts --no-progress; \
    composer clear-cache

# ETAPE 3
COPY .env ./

# ETAPE 4
RUN composer dump-env prod

# ETAPE 5-11
COPY assets ./assets
COPY bin ./bin
COPY config ./config
COPY migrations ./migrations
COPY public ./public
COPY src ./src
COPY templates ./templates

# ETAPE 12
RUN set -eux; \
mkdir -p var/cache var/log; \
composer dump-autoload --classmap-authoritative --no-dev; \
composer run-script --no-dev post-install-cmd; \
chmod +x bin/console; sync


# ETAPE
FROM contacts_nginx AS contacts_nginx_prod

# Etape 1
COPY --from=contacts_php_prod /symfony-contacts/public ./public
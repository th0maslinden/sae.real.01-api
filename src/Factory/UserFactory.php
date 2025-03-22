<?php

namespace App\Factory;

use App\Entity\User;
use Jdenticon\Identicon;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return User::class;
    }

    /**
     * Create an avatar resource using Jdenticon.
     *
     * @return resource
     */
    protected static function createAvatar(string $value)
    {
        $icon = new Identicon([
            'value' => $value,
            'size' => 50,
        ]);

        return fopen($icon->getImageDataUri('png'), 'r');
    }

    /**
     * Transliterate and sanitize a string.
     */
    protected static function transliterate(string $value): string
    {
        $transliterator = \Transliterator::create('Any-Latin; Latin-ASCII');
        $value = $transliterator->transliterate($value);

        return preg_replace('/[^a-zA-Z0-9]/', '', $value);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $faker = self::faker();

        $firstname = $faker->firstName;
        $lastname = $faker->lastName;
        $email = strtolower(
            self::transliterate($firstname).'.'.
            self::transliterate($lastname).
            '@'.$faker->domainName
        );

        return [
            'avatar' => self::createAvatar("$firstname $lastname"),
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'login' => self::faker()->unique()->numerify($lastname.'###'),
            'password' => 'test',
            'roles' => ['ROLE_USER'],
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (User $user): void {
                $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
            });
    }
}

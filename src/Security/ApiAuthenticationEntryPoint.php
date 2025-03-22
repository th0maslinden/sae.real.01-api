<?php

// src/Security/ApiAuthenticationEntryPoint.php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class ApiAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function start(Request $request, ?AuthenticationException $authException = null): RedirectResponse
    {
        /** @var string $route current route */
        $route = $request->get('_route');
        if (str_starts_with($route, '_api_')) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED);
        }
        $request->getSession()->getFlashBag()->add('note', 'Vous devez vous authentifiez pour accéder à cette page.');

        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}

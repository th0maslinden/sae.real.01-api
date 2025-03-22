<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController
{
    #[Route('/redirect-to-frontend', name: 'redirect_to_frontend')]
    public function redirectToFrontend(): RedirectResponse
    {
        return new RedirectResponse('http://localhost:5173/');
    }
}
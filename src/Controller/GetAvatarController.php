<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class GetAvatarController extends AbstractController
{
    public function __invoke(User $user): Response
    {
        $avatar = stream_get_contents($user->getAvatar(), null, 0);
        $response = new Response($avatar);
        $response->headers->set('Content-Type', 'image/png');

        return $response;
    }
}

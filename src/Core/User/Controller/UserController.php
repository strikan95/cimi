<?php

namespace App\Core\User\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/api/v1/users/me', name: 'api.v1.users.me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();
        return $this->json($user, Response::HTTP_OK);
    }
}
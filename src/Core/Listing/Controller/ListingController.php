<?php

namespace App\Core\Listing\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListingController extends AbstractController
{
    #[Route('/api/v1/listings', name: 'api.v1.listings.create', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json(['hello'], Response::HTTP_OK);
    }
}

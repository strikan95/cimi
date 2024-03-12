<?php

namespace App\Core\Host\Controller;

use App\Core\Host\Repository\HostListingRepository;
use App\Core\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HostListingController extends AbstractController
{
    public function __construct(private HostListingRepository $repository)
    {
    }

    #[
        Route(
            '/api/v1/hosts/{id}/listings',
            name: 'api.v1.hosts.listings.get',
            methods: ['GET'],
        ),
    ]
    function getHostListings(string $id): JsonResponse
    {
        if ($id === 'me') {
            /** @var User $user */
            $user = $this->getUser();
            $id = $user->getId();
        }

        $listings = $this->repository->fetchHostListings($id);

        return $this->json($listings, Response::HTTP_OK);
    }
}

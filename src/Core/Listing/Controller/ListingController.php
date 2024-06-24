<?php

namespace App\Core\Listing\Controller;

use App\Core\Listing\Entity\Listing;
use App\Core\Listing\Repository\ListingRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class ListingController extends AbstractController
{
    private readonly array $serializationContext;

    public function __construct(
        private EntityManagerInterface $em,
        private ListingRepository $listingRepository,
    ) {
        $this->serializationContext = (new ObjectNormalizerContextBuilder())
            ->withGroups(['listing_details', 'in_listing_details', 'host'])
            ->toArray();
    }

    #[
        Route(
            '/api/v1/listings/{id}',
            name: 'api.v1.listings.get',
            methods: ['GET'],
        ),
    ]
    public function getById(int $id): JsonResponse
    {
        $listing = $this->listingRepository->fetchListingDetails($id);
        return $this->json(
            $listing,
            Response::HTTP_OK,
            context: $this->serializationContext,
        );
    }

    #[
        Route(
            '/api/v1/listings/{id}',
            name: 'api.v1.listings.delete',
            methods: ['DELETE'],
        ),
    ]
    public function deleteListing(Listing $listing): JsonResponse
    {
        $this->em->remove($listing);
        $this->em->flush();

        return $this->json(
            ['message' => 'Listing deleted successfully'],
            Response::HTTP_OK,
            context: $this->serializationContext,
        );
    }
}

<?php

namespace App\Core\Listing\Controller;

use App\Core\Listing\Entity\Listing;
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
        private EntityManagerInterface $em
    ){
        $this->serializationContext = (new ObjectNormalizerContextBuilder())
            ->withGroups(['listing_details', 'in_listing_details'])
            ->toArray();
    }

    #[Route('/api/v1/listings/{listing}', name: 'api.v1.listings.get', methods: ['GET'])]
    public function getById(Listing $listing): JsonResponse
    {
        return $this->json(
            $listing,
            Response::HTTP_OK,
            context: $this->serializationContext
        );
    }
}

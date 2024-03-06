<?php

namespace App\Core\Listing\Controller;

use App\Core\Listing\Entity\Amenity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AmenityController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em){}

    #[Route('/api/v1/amenities', name: 'api.v1.amenities.index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json(
            ['amenities' => $this->em->getRepository(Amenity::class)->findAll()],
            Response::HTTP_OK
        );
    }
}

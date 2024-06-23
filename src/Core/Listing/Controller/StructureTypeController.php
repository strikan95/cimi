<?php

namespace App\Core\Listing\Controller;

use App\Core\Listing\Entity\Amenity;
use App\Core\Listing\Entity\StructureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StructureTypeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[
        Route(
            '/api/v1/structure-types',
            name: 'api.v1.structure.types.index',
            methods: ['GET'],
        ),
    ]
    public function index(): JsonResponse
    {
        return $this->json(
            [
                'structureTypes' => $this->em
                    ->getRepository(StructureType::class)
                    ->findAll(),
            ],
            Response::HTTP_OK,
        );
    }
}

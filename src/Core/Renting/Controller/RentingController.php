<?php

namespace App\Core\Renting\Controller;

use App\Core\Listing\Entity\Listing;
use App\Core\Renting\Entity\RentPeriod;
use App\Core\Renting\Form\RentPeriodType;
use App\Shared\ExceptionHandling\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RentingController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[
        Route(
            '/api/v1/listings/{listing}/occupancy',
            name: 'api.v1.listing.occupancy.index',
            methods: ['GET'],
        ),
    ]
    public function index(Listing $listing): JsonResponse
    {
        if ($listing->getHost() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->json(
            ['occupancy' => $listing->getRentPeriods()],
            Response::HTTP_OK,
        );
    }

    #[
        Route(
            '/api/v1/listings/{listing}/occupancy',
            name: 'api.v1.listing.occupancy.create',
            methods: ['POST'],
        ),
    ]
    public function createNewRentingPeriod(
        Request $request,
        Listing $listing,
    ): JsonResponse {
        if ($listing->getHost() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $rentPeriod = new RentPeriod();
        $form = $this->createForm(RentPeriodType::class, $rentPeriod);

        $this->processForm($form, $request);
        if (!$form->isValid()) {
            return $this->createValidationErrorResponse($form);
        }

        $listing->addRentPeriod($rentPeriod);
        $this->em->persist($listing);
        $this->em->flush();

        return $this->json([], Response::HTTP_OK);
    }

    private function processForm(FormInterface $form, Request $input): void
    {
        $data = json_decode($input->getContent(), true);
        if (null === $data) {
            //todo: throw error
        }

        $form->submit($data, false);
    }

    private function createValidationErrorResponse(FormInterface $form)
    {
        $errors = $this->resolveFormErrors($form);
        throw new ValidationException($errors);
    }

    private function resolveFormErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->resolveFormErrors($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}

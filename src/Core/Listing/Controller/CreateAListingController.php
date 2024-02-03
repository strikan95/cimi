<?php

namespace App\Core\Listing\Controller;

use App\Core\Listing\Entity\Listing;
use App\Core\Listing\Form\ListingType;
use App\Utils\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateAListingController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ){}

    #[Route('/api/v1/create-a-listing/new', name: 'api.v1.create-a-listing.draft.new', methods: ['POST'])]
    public function initializeDraft(): JsonResponse
    {
        $propertyListing = new Listing();
        $propertyListing->setHost($this->getUser());

        $this->em->persist($propertyListing);
        $this->em->flush();

        return $this->json($propertyListing,
            Response::HTTP_OK);
    }

    #[Route('/api/v1/create-a-listing/{draft}', name: 'api.v1.create-a-listing.draft.get', methods: ['GET'])]
    public function getDraftData(Listing $draft): JsonResponse
    {
        $bla = $draft;
        return $this->json($draft,
            Response::HTTP_OK);
    }

    #[Route('/api/v1/create-a-listing/{draft}/{step}', name: 'api.v1.create-a-listing.draft.step', methods: ['POST'])]
    public function handleStep(Request $request, Listing $draft, string $step): JsonResponse
    {
        if (!in_array($step, ListingType::STEPS)) {
            throw new \Exception('Unknown step', 11);
        }

        $form = $this->createForm(
            ListingType::class,
            $draft,
            ['step' => $step]
        );

        $this->processForm($request, $form);
        if (!$form->isValid()) {
            return $this->createValidationErrorResponse($form);
        }

        /** @var Listing $listing */
        $listing = $form->getData();
        $listing->setLastUpdatedStep($step);

        $this->em->persist($listing);
        $this->em->flush();

        return $this->json($listing,
            Response::HTTP_OK);
    }

    private function processForm(Request $request, FormInterface $form): void
    {
        $data = json_decode($request->getContent(), true);
        if (null === $data) {
            //todo: throw error
        }

        $form->submit($data);
    }

    private function createValidationErrorResponse(FormInterface $form)
    {
        $errors = $this->resolveFormErrors($form);
        throw new ValidationException($errors);
    }

    private function resolveFormErrors(FormInterface $form): array
    {
        $errors = array();
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
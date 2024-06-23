<?php

namespace App\Core\Listing\Controller;

use App\Core\Listing\DraftBuildSteps;
use App\Core\Listing\Entity\Image;
use App\Core\Listing\Entity\Listing;
use App\Core\Listing\Entity\StructureType;
use App\Core\Listing\Form\DraftType;
use App\Core\Listing\Form\ImageType;
use App\Core\User\Entity\User;
use App\Shared\ExceptionHandling\Exception\ValidationException;
use App\Shared\Service\FileUploader\FileUploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class DraftController extends AbstractController
{
    private readonly array $serializationContext;

    public function __construct(
        private EntityManagerInterface $em,
        private SerializerInterface $serializer,
    ) {
        $this->serializationContext = (new ObjectNormalizerContextBuilder())
            ->withGroups(['draft', 'amenities_in_draft'])
            ->toArray();
    }

    #[
        Route(
            '/api/v1/draft/{draft}',
            name: 'api.v1.draft.get',
            methods: ['GET'],
        ),
    ]
    public function getDraft(Listing $draft): JsonResponse
    {
        return $this->createApiSuccessResponse($draft);
    }

    #[Route('/api/v1/draft', name: 'api.v1.draft.create', methods: ['POST'])]
    public function createDraft(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $draft = new Listing($user);

        $this->em->persist($draft);
        $this->em->flush();

        return $this->json(
            $draft,
            Response::HTTP_OK,
            context: $this->serializationContext,
        );
    }

    #[
        Route(
            '/api/v1/draft/{draft}/structure-type',
            name: 'api.v1.draft.structure',
            methods: ['PATCH'],
        ),
    ]
    public function updateStructureType(
        Request $request,
        Listing $draft,
    ): JsonResponse {
        $vla = $request->getContent();
        return $this->resolveStep(
            DraftBuildSteps::StructureType,
            $draft,
            $request,
        );
    }

    #[
        Route(
            '/api/v1/draft/{draft}/title',
            name: 'api.v1.draft.title',
            methods: ['PATCH'],
        ),
    ]
    public function updateTitle(Request $request, Listing $draft): JsonResponse
    {
        return $this->resolveStep(DraftBuildSteps::Title, $draft, $request);
    }

    #[
        Route(
            '/api/v1/draft/{draft}/description',
            name: 'api.v1.draft.description',
            methods: ['PATCH'],
        ),
    ]
    public function updateDescription(
        Request $request,
        Listing $draft,
    ): JsonResponse {
        return $this->resolveStep(
            DraftBuildSteps::Description,
            $draft,
            $request,
        );
    }

    #[
        Route(
            '/api/v1/draft/{draft}/location',
            name: 'api.v1.draft.location',
            methods: ['PATCH'],
        ),
    ]
    public function updateLocation(
        Request $request,
        Listing $draft,
    ): JsonResponse {
        $data = [
            'location' => json_decode($request->getContent(), true),
        ];
        return $this->resolveStep(DraftBuildSteps::Location, $draft, $data);
    }

    #[
        Route(
            '/api/v1/draft/{draft}/amenities',
            name: 'api.v1.draft.amenities',
            methods: ['PATCH'],
        ),
    ]
    public function updateAmenities(
        Request $request,
        Listing $draft,
    ): JsonResponse {
        return $this->resolveStep(DraftBuildSteps::Amenities, $draft, $request);
    }

    #[
        Route(
            '/api/v1/draft/{draft}/image',
            name: 'api.v1.draft.image.get',
            methods: ['GET'],
        ),
    ]
    public function getImages(Listing $draft): JsonResponse
    {
        return $this->json(
            $draft->getImages(),
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json'],
            context: (new ObjectNormalizerContextBuilder())
                ->withGroups(['draft', 'amenities_in_draft'])
                ->toArray(),
        );
    }

    #[
        Route(
            '/api/v1/draft/{draft}/image',
            name: 'api.v1.draft.image.add',
            methods: ['POST'],
        ),
    ]
    public function addImage(Request $request, Listing $draft): JsonResponse
    {
        $form = $this->createForm(ImageType::class, new Image());
        $this->processForm($form, ['image' => $request->files->get('image')]);
        if (!$form->isValid()) {
            return $this->createValidationErrorResponse($form);
        }

        $image = $form->getData();

        $draft->addImage($image);
        $this->em->persist($draft);
        $this->em->flush();

        return $this->createApiSuccessResponse($image);
    }

    #[
        Route(
            '/api/v1/draft/{draft}/image/{image}',
            name: 'api.v1.draft.image.delete',
            methods: ['DELETE'],
        ),
    ]
    public function removeImage(
        Listing $draft,
        Image $image,
        FileUploaderInterface $fileUploader,
    ): JsonResponse {
        if (!$draft->removeImage($image)) {
            // Todo throw not found.
        }

        $res = $fileUploader
            ->getUploader()
            ->uploadApi()
            ->destroy($image->getId());
        if ($res['result'] !== 'ok') {
            // Todo throw unable to remove image error
        }

        $this->em->persist($draft);
        $this->em->flush();

        return $this->json(
            ['message' => 'ok'],
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json'],
        );
    }

    private function resolveStep(
        DraftBuildSteps $step,
        Listing $draft,
        Request|array $input,
    ): JsonResponse {
        $form = $this->createForm(
            DraftType::class,
            $draft,
            options: ['validation_groups' => $step->value],
        );

        $this->processForm($form, $input);
        if (!$form->isValid()) {
            return $this->createValidationErrorResponse($form);
        }

        $draft->setLastUpdatedStep($step->value);
        $this->em->persist($draft);
        $this->em->flush();

        return $this->createApiSuccessResponse($draft);
    }

    private function processForm(
        FormInterface $form,
        Request|array $input,
    ): void {
        $data =
            $input instanceof Request
                ? json_decode($input->getContent(), true)
                : $input;
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

    private function createApiSuccessResponse(
        object|array $entity,
    ): JsonResponse {
        return $this->json(
            $entity,
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json'],
            context: $this->serializationContext,
        );
    }
}

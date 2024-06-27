<?php

namespace App\Core\User\Controller;

use App\Core\Listing\Entity\Image;
use App\Core\Listing\Form\ImageType;
use App\Core\User\Entity\Embeddable\UserDetails;
use App\Core\User\Entity\User;
use App\Core\User\Form\DataClass\UserDetailsData;
use App\Core\User\Form\UserDetailsType;
use App\Shared\ExceptionHandling\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/api/v1/users/me', name: 'api.v1.users.me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();
        return $this->json($user, Response::HTTP_OK);
    }

    #[
        Route(
            '/api/v1/users/me/profile',
            name: 'api.v1.users.me.profile',
            methods: ['POST'],
        ),
    ]
    public function setupProfile(Request $request): JsonResponse
    {
        if (!in_array('ROLE_NEW', $this->getUser()->getRoles())) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();
        $requestData = json_decode($request->getContent(), true);

        $dto = new UserDetailsData();
        $form = $this->createForm(UserDetailsType::class, $dto);

        $form->submit($requestData['userDetails']);
        $errors = $this->resolveFormErrors($form);
        if (count($errors) > 0) {
            return $this->json(
                ['errors' => $errors],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $user->updateUserDetails(UserDetails::build($dto));
        $user->setRole($requestData['role']);

        $this->em->persist($user);
        $this->em->flush();

        return $this->json($user, Response::HTTP_OK);
    }

    #[
        Route(
            '/api/v1/users/me/image',
            name: 'api.v1.users.me.image',
            methods: ['POST'],
        ),
    ]
    public function updateProfileImage(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ImageType::class, new Image());
        $form->submit(['image' => $request->files->get('image')], false);

        $errors = $this->resolveFormErrors($form);
        if (count($errors) > 0) {
            return $this->json(
                ['errors' => $errors],
                Response::HTTP_BAD_REQUEST,
            );
        }

        /** @var Image $image */
        $image = $form->getData();
        $user->setPicture($image->getUrl());

        $this->em->persist($user);
        $this->em->flush();

        return $this->json($user, Response::HTTP_OK);
    }

    #[
        Route(
            '/api/v1/users/{user}',
            name: 'api.v1.users.user_details.update',
            methods: ['PATCH'],
        ),
    ]
    public function updateUserDetails(
        Request $request,
        User $user,
    ): JsonResponse {
        if (
            $this->getUser()->getUserIdentifier() !== $user->getUserIdentifier()
        ) {
            throw $this->createAccessDeniedException();
        }

        $dto = new UserDetailsData();
        $form = $this->createForm(UserDetailsType::class, $dto);

        $form->submit(json_decode($request->getContent(), true));
        $errors = $this->resolveFormErrors($form);
        if (count($errors) > 0) {
            return $this->json(
                ['errors' => $errors],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $user->updateUserDetails(UserDetails::build($dto));
        $this->em->persist($user);
        $this->em->flush();

        return $this->json(
            $user,
            Response::HTTP_OK,
            context: ['groups' => ['read', 'author']],
        );
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

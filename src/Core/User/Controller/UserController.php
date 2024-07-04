<?php

namespace App\Core\User\Controller;

use App\Core\Listing\Entity\Image;
use App\Core\Listing\Form\ImageType;
use App\Core\User\Entity\User;
use App\Core\User\Form\UserRegisterType;
use App\Core\User\Form\UserUpdateForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/api/v1/register', name: 'api.v1.register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $form = $this->createForm(UserRegisterType::class, new User());
        $form->submit(json_decode($request->getContent(), true), false);

        /** @var User $user */
        $user = $form->getData();

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );
        $user->setPassword($hashedPassword);

        $this->em->persist($user);
        $this->em->flush();

        return $this->json($user, Response::HTTP_OK);
    }

    #[Route('/api/v1/users/me', name: 'api.v1.users.me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();
        return $this->json($user, Response::HTTP_OK);
    }

    #[Route('/api/v1/users/me', name: 'api.v1.users.update', methods: ['POST'])]
    public function update(Request $request): JsonResponse
    {
        $user = $this->getUser();

        $form = $this->createForm(UserUpdateForm::class, $user);
        $form->submit(json_decode($request->getContent(), true), false);

        /** @var User $user */
        $user = $form->getData();

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
        $user->setPicture($image);

        $this->em->persist($user);
        $this->em->flush();

        return $this->json($user, Response::HTTP_OK);
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

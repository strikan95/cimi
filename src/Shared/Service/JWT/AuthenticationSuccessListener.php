<?php

namespace App\Shared\Service\JWT;

// src/App/EventListener/AuthenticationSuccessListener.php
use App\Core\User\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        /** @var User $user */
        $user = $event->getUser();

/*        if (!$user instanceof UserInterface) {
            return;
        }*/

        $data['id'] = $user->getId();
        $data['roles'] = $user->getRoles();
        $data['name'] = $user->getFirstName() . ' ' . $user->getLastName();
        $data['email'] = $user->getEmail();
        $data['image'] = $user->getPicture();

        $event->setData($data);
    }
}


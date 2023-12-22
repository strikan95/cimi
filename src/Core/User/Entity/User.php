<?php

namespace App\Core\User\Entity;

use App\Core\User\Entity\Embeddable\UserDetails;
use App\Core\User\Entity\Embeddable\UserIdentity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Embedded(class: UserIdentity::class, columnPrefix: false)]
    private UserIdentity $userIdentity;

    #[ORM\Embedded(class: UserDetails::class, columnPrefix: false)]
    private ?UserDetails $userDetails = null;

    public static function register(UserIdentity $userIdentity): User
    {
        $_user = new self();
        $_user->userIdentity = $userIdentity;
        return $_user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentity(): UserIdentity
    {
        return $this->userIdentity;
    }

    public function getUserDetails(): ?UserDetails
    {
        return $this->userDetails;
    }
}

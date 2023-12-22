<?php

namespace App\Core\User\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class UserDetails
{
    #[ORM\Column(type: "string", nullable: true)]
    private string $firstName;

    #[ORM\Column(type: "string", nullable: true)]
    private string $lastName;
}
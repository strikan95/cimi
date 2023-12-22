<?php

namespace App\Core\User\Entity\Embeddable;

use App\Core\User\Form\DataClass\UserDetailsData;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class UserDetails
{
    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $firstName;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $lastName;

    public static function build(UserDetailsData $data): UserDetails
    {
        $_userDetails = new self();
        $_userDetails->firstName = $data->firstName;
        $_userDetails->lastName = $data->lastName;
        return $_userDetails;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }
}
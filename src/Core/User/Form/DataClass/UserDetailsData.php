<?php

namespace App\Core\User\Form\DataClass;

use Symfony\Component\Validator\Constraints as Assert;

class UserDetailsData
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    public string $firstName;

    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    public string $lastName;
}
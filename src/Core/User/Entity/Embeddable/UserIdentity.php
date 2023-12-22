<?php

namespace App\Core\User\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class UserIdentity
{
    #[ORM\Column(type: "string", length: 64)]
    private string $sub;

    #[ORM\Column(type: "string", length: 64)]
    private string $email;

    public static function build(
        string $sub,
        string $email
    ): UserIdentity
    {
        $_userIdentity = new self();
        $_userIdentity->sub = $sub;
        $_userIdentity->email = $email;

        return $_userIdentity;
    }

    public function getSub(): string
    {
        return $this->sub;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
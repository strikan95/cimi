<?php

namespace App\Core\User\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class UserIdentity
{
    #[ORM\Column(type: "string")]
    private string $sub;

    #[ORM\Column(type: "string")]
    private string $email;

    #[ORM\Column(type: "string")]
    private string $username;

    public static function build(
        string $sub,
        string $email,
        string $username
    ): UserIdentity
    {
        $_userIdentity = new self();
        $_userIdentity->sub = $sub;
        $_userIdentity->email = $email;
        $_userIdentity->username = $username;

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

    public function getUsername(): string
    {
        return $this->username;
    }
}
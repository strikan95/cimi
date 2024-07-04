<?php

namespace App\Core\User\Entity;

use App\Core\Listing\Entity\Image;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['in_listing_details'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 64)]
    private string $email;

    #[ORM\Column(type: 'string', length: 128)]
    private string $password;

    #[ORM\Column(type: 'simple_array')]
    private array $roles = [];

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private string $firstName;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private string $lastName;

    #[ORM\Column(nullable: true)]
    private ?string $picture;

    public function User(): User
    {
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        return;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(string $role): void
    {
        $this->roles = [$role];
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(Image $picture): void
    {
        $this->picture = $picture->getThumbnailUrl();
    }
}

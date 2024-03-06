<?php

namespace App\Core\Listing\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

class Amenity
{
    #[Groups(['amenities_in_draft'])]
    private ?int $id = null;
    #[Groups(['amenities_in_draft', 'in_listing_details'])]
    private string $name;

    #[Groups(['in_listing_details'])]
    private string $iconUrl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIconUrl(): string
    {
        return $this->iconUrl;
    }

    public function setIconUrl(string $iconUrl): void
    {
        $this->iconUrl = $iconUrl;
    }
}
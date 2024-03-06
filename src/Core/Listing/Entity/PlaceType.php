<?php

namespace App\Core\Listing\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

class PlaceType
{
    private int $id;

    #[Groups(['draft', 'in_listing_details'])]
    private string $name;

    #[Groups(['in_listing_details'])]
    private string $iconUrl;

    public static function new($id, $name, $iconUrl): PlaceType
    {
        $_self = new self();
        $_self->id = $id;
        $_self->name = $name;
        $_self->iconUrl = $iconUrl;

        return $_self;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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
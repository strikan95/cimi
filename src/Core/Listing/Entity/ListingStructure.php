<?php

namespace App\Core\Listing\Entity;

class ListingStructure
{
    private int $id;
    private string $name;
    private string $iconUrl;

    public static function new($id, $name, $iconUrl): ListingStructure
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
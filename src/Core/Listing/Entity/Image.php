<?php

namespace App\Core\Listing\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

class Image
{
    #[Groups(['draft', 'in_listing_details'])]
    private string $id;

    #[Groups(['draft'])]
    private string $url;

    #[Groups(['draft', 'in_listing_details'])]
    private string $thumbnailUrl;

    private ?Listing $listing;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getThumbnailUrl(): string
    {
        return $this->thumbnailUrl;
    }

    public function setThumbnailUrl(string $thumbnailUrl): void
    {
        $this->thumbnailUrl = $thumbnailUrl;
    }

    public function getListing(): ?Listing
    {
        return $this->listing;
    }

    public function setListing(?Listing $listing): void
    {
        $this->listing = $listing;
    }
}
<?php

namespace App\Core\Listing\Entity;

use App\Core\Listing\Entity\Embeddable\ListingLocation;
use App\Core\User\Entity\User;
use App\ORM\CustomTypes\ListingStatusType;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class Listing
{
    private ?int $id = null;
    #[Assert\Length(min: 10)]
    private ?string $title = null;
    #[Assert\Length(min: 10)]
    private ?string $description = null;
    private ?ListingStructure $structureType = null;
    private ?ListingLocation $location = null;
    private ?User $host = null;
    private ?string $coverImageUrl = null;

    private ?StructureType $structureType = null;

    private ?PlaceType $placeType = null;
    private Collection $images;
    private Collection $amenities;
    private string $status;
    private ?string $lastUpdatedStep = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->amenities = new ArrayCollection();
        $this->location = new ListingLocation();
        $this->status = ListingStatusType::STATUS_DRAFT;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getLocation(): ?ListingLocation
    {
        return $this->location;
    }

    public function setLocation(?ListingLocation $location): void
    {
        $this->location = $location;
    }

    public function getHost(): ?User
    {
        return $this->host;
    }

    public function setHost(?User $host): void
    {
        $this->host = $host;
    }

    public function getStructureType(): ?StructureType
    {
        return $this->structureType;
    }

    public function setStructureType(?StructureType $structureType): void
    {
        $this->structureType = $structureType;
    }

    public function getLastUpdatedStep(): ?string
    {
        return $this->lastUpdatedStep;
    }

    public function setLastUpdatedStep(?string $lastUpdatedStep): void
    {
        $this->lastUpdatedStep = $lastUpdatedStep;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): void
    {
        $image->setListing($this);
        $this->images->add($image);
    }

    public function removeImage(Image $image): bool
    {
        $image->setListing(null);
        return $this->images->removeElement($image);
    }

    public function getAmenities(): Collection
    {
        return $this->amenities;
    }

    public function setAmenities(Collection $amenities): void
    {
        $this->amenities = $amenities;
    }

    public function getPlaceType(): ?PlaceType
    {
        return $this->placeType;
    }

    public function setPlaceType(?PlaceType $placeType): void
    {
        $this->placeType = $placeType;
    }
}

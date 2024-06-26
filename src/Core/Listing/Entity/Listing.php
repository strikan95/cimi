<?php

namespace App\Core\Listing\Entity;

use App\Core\Listing\Entity\Embeddable\ListingLocation;
use App\Core\Renting\Entity\RentPeriod;
use App\Core\User\Entity\User;
use App\Shared\ORM\ListingStatus\ListingStatusType;
use App\Shared\ORM\Spatial\PhpObject\Point;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class Listing
{
    #[Groups(['draft', 'list', 'listing_details'])]
    private ?int $id = null;

    #[Groups(['host'])]
    private User $host;

    #[Groups(['draft', 'list', 'listing_details'])]
    #[Assert\NotBlank(groups: ['title', 'all'])]
    #[Assert\Length(max: 32, groups: ['title', 'all'])]
    private ?string $title = '';

    #[Groups(['draft', 'listing_details'])]
    #[Assert\NotBlank(groups: ['description', 'all'])]
    #[Assert\Length(max: 48, groups: ['description', 'all'])]
    private ?string $description = '';

    #[Groups(['list', 'listing_details'])]
    private ?int $price = 0;

    #[Groups(['draft', 'list'])]
    private ?string $coverImageUrl = '';

    #[Groups(['draft', 'list', 'listing_details'])]
    private ListingLocation $location;

    #[Groups(['draft', 'listing_details'])]
    private ?StructureType $structureType = null;

    #[Groups(['draft', 'listing_details'])]
    private ?PlaceType $placeType = null;

    #[Groups(['draft', 'listing_details'])]
    private Collection $images;

    #[Groups(['draft', 'listing_details'])]
    private Collection $amenities;

    #[Groups(['draft', 'listing_details'])]
    private Collection $rentPeriods;

    #[Groups(['draft'])]
    private string $status;

    #[Groups(['draft'])]
    private string $lastUpdatedStep;

    public function __construct(User $host)
    {
        $this->host = $host;
        $this->images = new ArrayCollection();
        $this->rentPeriods = new ArrayCollection();
        $this->amenities = new ArrayCollection();
        $this->location = ListingLocation::init();
        $this->status = ListingStatusType::STATUS_DRAFT;
        $this->lastUpdatedStep = 'init';
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

    public function getHost(): User
    {
        return $this->host;
    }

    public function setHost(User $host): void
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

    public function getLastUpdatedStep(): string
    {
        return $this->lastUpdatedStep;
    }

    public function setLastUpdatedStep(string $lastUpdatedStep): void
    {
        $this->lastUpdatedStep = $lastUpdatedStep;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): void
    {
        if ($this->images->count() <= 0) {
            $this->coverImageUrl = $image->getUrl();
        }
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCoverImageUrl(): ?string
    {
        return $this->coverImageUrl;
    }

    public function setCoverImageUrl(?string $coverImageUrl): void
    {
        $this->coverImageUrl = $coverImageUrl;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    public function getPlaceType(): ?PlaceType
    {
        return $this->placeType;
    }

    public function setPlaceType(?PlaceType $placeType): void
    {
        $this->placeType = $placeType;
    }

    public function getRentPeriods(): Collection
    {
        return $this->rentPeriods;
    }

    private function canAddRentPeriod(RentPeriod $period): bool
    {
        foreach ($this->rentPeriods as $rentPeriod) {
            if ($period->getEndDate() >= $rentPeriod->getStartDate()) {
                if ($period->getStartDate() <= $rentPeriod->getEndDate()) {
                    return false;
                }
            }
        }
        return true;
    }

    public function addRentPeriod(RentPeriod $period): void
    {
        if (!$this->canAddRentPeriod($period)) {
            throw new \Exception('rent period clashes');
        }

        $this->rentPeriods[] = $period;
        $period->setListing($this);
    }

    public function setRentPeriods(Collection $rentPeriods): void
    {
        $this->rentPeriods = $rentPeriods;
    }
}

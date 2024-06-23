<?php

namespace App\Core\Renting\Entity;

use App\Core\Listing\Entity\Listing;
use DateTime;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

class RentPeriod
{
    private ?int $id = null;

    #[Groups(['listing_details'])]
    private DateTime $startDate;

    #[Groups(['listing_details'])]
    private DateTime $endDate;

    #[Ignore]
    private Listing $listing;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getListing(): Listing
    {
        return $this->listing;
    }

    public function setListing(Listing $listing): void
    {
        $this->listing = $listing;
    }
}

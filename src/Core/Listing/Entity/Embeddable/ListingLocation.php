<?php

namespace App\Core\Listing\Entity\Embeddable;

use App\Shared\ORM\Spatial\PhpObject\Point;
use Symfony\Component\Serializer\Annotation\Groups;

class ListingLocation
{
    #[Groups(['draft', 'list'])]
    private string $country;

    #[Groups(['draft', 'list'])]
    private string $city;

    #[Groups(['draft'])]
    private string $postCode;

    #[Groups(['draft'])]
    private string $street;

    #[Groups(['draft'])]
    private string $streetNumber;

    #[Groups(['draft', 'listing_details'])]
    private float $latitude;

    #[Groups(['draft', 'listing_details'])]
    private float $longitude;

    private Point $coordinates;

    public static function init(): ListingLocation
    {
        $self = new self();
        $self->country = '';
        $self->city = '';
        $self->postCode = '';
        $self->street = '';
        $self->streetNumber = '';
        $self->coordinates = new Point(0, 0);
        $self->longitude = 0;
        $self->latitude = 0;
        return $self;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getPostCode(): string
    {
        return $this->postCode;
    }

    public function setPostCode(string $postCode): void
    {
        $this->postCode = $postCode;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getStreetNumber(): string
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(string $streetNumber): void
    {
        $this->streetNumber = $streetNumber;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
        $this->coordinates = new Point(
            $latitude,
            $this->coordinates->getLongitude(),
        );
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
        $this->coordinates = new Point(
            $this->coordinates->getLatitude(),
            $longitude,
        );
    }

    public function getCoordinates(): Point
    {
        return $this->coordinates;
    }

    public function setCoordinates(Point $coordinates): void
    {
        $this->coordinates = $coordinates;
    }
}

<?php

namespace App\Core\Listing\Entity\Embeddable;

class ListingLocation
{
    private string $country;

    private string $city;

    private int $zipCode;

    private string $street;

    private string $streetNumber;

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

    public function getZipCode(): int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): void
    {
        $this->zipCode = $zipCode;
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
}
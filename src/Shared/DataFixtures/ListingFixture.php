<?php

namespace App\Shared\DataFixtures;

use App\Core\Listing\Entity\Amenity;
use App\Core\Listing\Entity\Embeddable\ListingLocation;
use App\Core\Listing\Entity\Image;
use App\Core\Listing\Entity\Listing;
use App\Core\Listing\Entity\PlaceType;
use App\Core\Listing\Entity\StructureType;
use App\Core\Renting\Entity\RentPeriod;
use App\Core\User\Entity\User;
use App\ORM\CustomTypes\Class\GeoPoint;
use App\Shared\ORM\Spatial\PhpObject\Point;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ListingFixture extends BaseFixture implements DependentFixtureInterface
{
    const OSBB = [45.525833, 18.589554, 45.581608, 18.768768];
    private array $placeHolderImageIds;

    public function __construct()
    {
        $client = new Client(['base_uri' => 'https://picsum.photos/v2/']);
        try {
            $response = $client->request('GET', 'list?page=0&limit=100');
            $data = json_decode($response->getBody());
            foreach ($data as $image) {
                $this->placeHolderImageIds[] = $image->id;
            }
        } catch (GuzzleException $e) {
            die($e->getMessage());
        }
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            AmenityFixture::class,
            StructureTypeFixture::class,
            PlaceTypeFixture::class,
        ];
    }

    protected function loadData(ObjectManager $manager): void
    {
        $this->createManyAlternative(200, function () {
            $propertyListing = new Listing(
                $this->getRandomReference(User::class),
            );
            $propertyListing->setTitle($this->faker->realText(32));
            $propertyListing->setDescription($this->faker->realText(46));
            $propertyListing->setStructureType(
                $this->getRandomReference(StructureType::class),
            );
            $propertyListing->setPlaceType(
                $this->getRandomReference(PlaceType::class),
            );
            $propertyListing->setPrice(rand(300, 2000));
            $propertyListing->setAmenities(
                $this->getRandomNReferences(Amenity::class, 6, 26),
            );
            $propertyListing->setLocation($this->generateRandomLocation());
            $propertyListing->setStatus('approved');
            $propertyListing->setLastUpdatedStep('finish');

            for ($i = 0; $i < rand(4, 10); $i++) {
                $propertyListing->addImage($this->generateRandomImage());
            }

            foreach ($this->generateNRandomRentPeriod(0, 6) as $rentPeriod) {
                $propertyListing->addRentPeriod($rentPeriod);
            }

            $coverImg = $this->generateRandomImage();
            $propertyListing->setCoverImageUrl($coverImg->getThumbnailUrl());
            $propertyListing->addImage($coverImg);

            return $propertyListing;
        });

        $manager->flush();
    }

    private function generateNRandomRentPeriod(int $min, int $max): array
    {
        /** @var RentPeriod[] $periods */
        $periods = [];
        $n = rand($min, $max);

        for ($i = 0; $i < $n + 1; $i++) {
            $period = new RentPeriod();
            $startDate = null;
            if ($i == 0) {
                $startDate = $this->faker->dateTimeBetween(
                    '-3 years',
                    '-2 years',
                );
            } else {
                $startDate = clone $periods[$i - 1]->getEndDate();
            }

            $startDate->modify(sprintf('+%d months', rand(1, 12)));
            $endDate = clone $startDate;
            $endDate = $endDate->modify(sprintf('+%d months', rand(1, 12)));

            $period->setStartDate($startDate);
            $period->setEndDate($endDate);
            $periods[] = $period;
        }

        return $periods;
    }

    private function generateRandomImage(): Image
    {
        $randId = $this->faker->randomElement($this->placeHolderImageIds);

        $image = new Image();
        $image->setId($this->faker->uuid());
        $image->setUrl('https://picsum.photos/id/' . $randId . '/1440/720');
        $image->setThumbnailUrl(
            'https://picsum.photos/id/' . $randId . '/720/480',
        );

        return $image;
    }

    private function generateRandomLocation(): ListingLocation
    {
        $location = new ListingLocation();
        $location->setCountry('Croatia');
        $location->setCity('Osijek');
        $location->setPostCode(31000);
        $location->setStreet($this->faker->streetName());
        $location->setStreetNumber($this->faker->buildingNumber());

        $coords = $this->generateRandomCoordinate(self::OSBB);
        $location->setLatitude($coords[0]);
        $location->setLongitude($coords[1]);

        $location->setCoordinates(new Point($coords[0], $coords[1]));
        return $location;
    }

    private function generateRandomCoordinate(array $rect): array
    {
        return [
            $rect[0] + $this->randomFloat(0, $rect[2] - $rect[0]),
            $rect[1] + $this->randomFloat(0, $rect[3] - $rect[1]),
        ];
    }

    private function randomFloat($min = 0, $max = 1)
    {
        return $min + (mt_rand() / mt_getrandmax()) * ($max - $min);
    }
}

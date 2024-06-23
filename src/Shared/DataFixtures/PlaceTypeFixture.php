<?php

namespace App\Shared\DataFixtures;

use App\Core\Listing\Entity\PlaceType;
use Doctrine\Persistence\ObjectManager;

class PlaceTypeFixture extends BaseFixture
{
    private static array $placeTypes = [
        [
            'A room',
            'https://res.cloudinary.com/daxjoycqb/image/upload/v1707391902/icons/home_orfifl.svg',
        ],
        [
            'Whole place',
            'https://res.cloudinary.com/daxjoycqb/image/upload/v1707392042/icons/building_rrsxgv.svg',
        ],
    ];

    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(PlaceType::class, count(self::$placeTypes), function (
            PlaceType $placeType,
            $count,
        ) {
            $placeType->setName(self::$placeTypes[$count][0]);
            $placeType->setIconUrl(self::$placeTypes[$count][1]);
        });

        $manager->flush();
    }
}

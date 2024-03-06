<?php

namespace App\DataFixtures;

use App\Core\Listing\Entity\Amenity;
use App\Core\Listing\Entity\StructureType;
use App\Core\User\Entity\Embeddable\UserDetails;
use App\Core\User\Entity\Embeddable\UserIdentity;
use App\Core\User\Entity\User;
use App\Core\User\Form\DataClass\UserDetailsData;
use Doctrine\DBAL\Types\StringType;
use Doctrine\Persistence\ObjectManager;

class StructureTypeFixture extends BaseFixture
{
    private static array $structureTypes = [
        ['House', 'https://res.cloudinary.com/daxjoycqb/image/upload/v1707391902/home_orfifl.svg'],
        ['Apartment', 'https://res.cloudinary.com/daxjoycqb/image/upload/v1707392042/building_rrsxgv.svg']
    ];

    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(StructureType::class, count(self::$structureTypes), function (StructureType $structureType, $count) {
            $structureType->setName(self::$structureTypes[$count][0]);
            $structureType->setIconUrl(self::$structureTypes[$count][1]);
        });

        $manager->flush();
    }
}
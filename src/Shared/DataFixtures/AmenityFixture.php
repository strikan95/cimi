<?php

namespace App\Shared\DataFixtures;

use App\Core\Listing\Entity\Amenity;
use Doctrine\Persistence\ObjectManager;

class AmenityFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        // Name, IconUrl
        $csv = fopen(dirname(__FILE__) . '/amenities.csv', 'r');

        $i = 0;
        while (!feof($csv)) {
            if (0 == $i) {
                fgetcsv($csv);
                $i++;
                continue; //skip column names line
            }

            $line = fgetcsv($csv);
            $this->createOne(Amenity::class, function (Amenity $amenity) use (
                $line,
            ) {
                $amenity->setName($line[0]);
                $amenity->setIconUrl($line[1]);
            });
            $i++;
        }

        $manager->flush();
    }
}

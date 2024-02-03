<?php

namespace App\DataFixtures;

use App\Core\Listing\Entity\ListingStructure;
use App\Core\User\Entity\Embeddable\UserDetails;
use App\Core\User\Entity\Embeddable\UserIdentity;
use App\Core\User\Entity\User;
use App\Core\User\Form\DataClass\UserDetailsData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = User::register(
            UserIdentity::build('google-oauth2|100715050315245662548', 'buljevic.juraj@gmail.com'),
        );

        $userDetails = new UserDetailsData();
        $userDetails->firstName = 'Juraj';
        $userDetails->lastName = 'Buljevic';

        $user->updateUserDetails(UserDetails::build($userDetails));

        $manager->persist($user);

        $houseType = new ListingStructure();
        $houseType->setName('House');
        $houseType->setIconUrl('houseIconUrl');
        $manager->persist($houseType);


        $apartmentType = new ListingStructure();
        $apartmentType->setName('Apartment');
        $apartmentType->setIconUrl('apartmentIconUrl');
        $manager->persist($apartmentType);


        $manager->flush();
    }
}

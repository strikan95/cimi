<?php

namespace App\Shared\DataFixtures;

use App\Core\User\Entity\Embeddable\UserDetails;
use App\Core\User\Entity\Embeddable\UserIdentity;
use App\Core\User\Entity\User;
use App\Core\User\Form\DataClass\UserDetailsData;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(User::class, 300, function (User $user, $count) {
            if ($count == 0) {
                $user->setUserIdentity(
                    UserIdentity::build(
                        $_SERVER['ADMIN_SUB'],
                        $_SERVER['ADMIN_EMAIL'],
                    ),
                );
                $userDetails = new UserDetailsData();
                $userDetails->firstName = $this->faker->firstName();
                $userDetails->lastName = $this->faker->lastName();
                $user->updateUserDetails(UserDetails::build($userDetails));
            } else {
                $user->setUserIdentity(
                    UserIdentity::build(
                        $this->faker->sentence(2),
                        $this->faker->email(),
                    ),
                );
                $userDetails = new UserDetailsData();
                $userDetails->firstName = $this->faker->firstName();
                $userDetails->lastName = $this->faker->lastName();
                $user->updateUserDetails(UserDetails::build($userDetails));
            }
        });

        $manager->flush();
    }
}

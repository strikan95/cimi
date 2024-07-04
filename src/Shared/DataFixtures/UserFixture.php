<?php

namespace App\Shared\DataFixtures;

use App\Core\User\Entity\User;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        // "password" hashed -> $2y$13$IsHt6.rtUY522b6vFFozju5RBS1rF1Ud.eHO74Nc.Qn5ROMSDvUQ6

        $this->createMany(User::class, 300, function (User $user, $count) {
            $pass = "\$2y\$13\$7VVf9T3dHhjyabo0XWAbveCCiVDifM8P2WHuBf11YAPxXZKnWpX9.";
            if ($count == 0) {
                $user->setEmail('juraj.buljevic@gmail.com');
                $user->setPassword($pass);
                $user->setRoles('ROLE_HOST');

                $user->setFirstName('Juraj');
                $user->setLastName('Buljevic');
            } else {
                $user->setEmail($this->faker->email());
                $user->setPassword($pass);
                $user->setRoles('ROLE_HOST');

                $user->setFirstName($this->faker->firstName());
                $user->setLastName($this->faker->lastName());
            }
        });

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setId('100');
        $user->setLogin('login');
        $user->setPass('admin');
        $user->setPhone('911');
        $user->setRoles(['ROLE_TEST_ADMIN']);

        $manager->persist($user);
        $manager->flush();

        $this->addReference('ROLE_TEST_ADMIN', $user);
    }
}

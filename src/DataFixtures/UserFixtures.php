<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; ++$i) {
            $user = new User();

            $user
                ->setEmail("user$i@ynov.com")
                ->setPassword(
                    $this->passwordHasher->hashPassword($user, 'toto')
                );

            $manager->persist($user);
        }

        $manager->flush();
    }
}

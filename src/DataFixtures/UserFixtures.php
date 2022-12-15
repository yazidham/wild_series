<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $contributor = new User();
        $contributor->setEmail($faker->email());
//        $contributor->setPassword($this->passwordHasher->hashPassword($contributor, $faker->password(6, 10)));
        $contributor->setPassword($this->passwordHasher->hashPassword($contributor, 'contributor'));
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $manager->persist($contributor);

        $admin = new User();
        $admin->setEmail($faker->email());
//        $admin->setPassword($this->passwordHasher->hashPassword($contributor, $faker->password(6, 10)));
        $admin->setPassword($this->passwordHasher->hashPassword($contributor, 'admin'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
    }
}

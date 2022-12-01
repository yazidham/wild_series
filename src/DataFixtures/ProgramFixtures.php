<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 5; $i++) {
            $program = new Program();
            $program->setTitle('program_' . $i);
            $program->setSynopsis('Des zombies envahissent la terre');
            $program->setCategory($this->getReference('category_' . $i));
            $this->addReference('program_' . $i, $program);
            $manager->persist($program);
        }
        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}

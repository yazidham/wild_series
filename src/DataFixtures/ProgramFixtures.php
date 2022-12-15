<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 5; $i++) {
            $program = new Program();
            $program->setTitle('program_' . $i);
            $program->setSynopsis('Des zombies envahissent la terre');
            $program->setCategory($this->getReference('category_' . $i));
            $slug = $this->slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $program->setOwner($this->getReference('admin'));
            $this->addReference('program_' . $i, $program);
            $manager->persist($program);
        }
        $manager->flush();

    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}

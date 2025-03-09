<?php

namespace App\DataFixtures;

use App\Factory\RessourceFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RessourceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        RessourceFactory::createMany(10);

        $manager->flush();
    }
}

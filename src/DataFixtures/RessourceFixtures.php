<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Factory\RessourceFactory;

class RessourceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        RessourceFactory::createMany(10);

        $manager->flush();
    }
}

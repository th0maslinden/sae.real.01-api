<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use App\Factory\PatientFactory;
use App\Factory\ProfessionnelFactory;
use App\Factory\SeanceFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création de 5 utilisateurs génériques
        UserFactory::createMany(5);

        // Création de 10 patients
        $patients = PatientFactory::createMany(10);

        // Création de 5 professionnels
        $professionnels = ProfessionnelFactory::createMany(5);

        // Chaque patient a au moins 2 séances
        foreach ($patients as $patient) {
            SeanceFactory::createMany(2, function () use ($patient, $professionnels) {
                return [
                    'patient' => $patient,
                    'professionnel' => $professionnels[array_rand($professionnels)],
                ];
            });
        }

        // Chaque professionnel est affecté à au moins 1 séance
        foreach ($professionnels as $professionnel) {
            SeanceFactory::createOne([
                'professionnel' => $professionnel,
                'patient' => $patients[array_rand($patients)],
            ]);
        }
    }
}

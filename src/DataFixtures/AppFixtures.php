<?php

namespace App\DataFixtures;

use App\Factory\AdminFactory;
use App\Factory\PatientFactory;
use App\Factory\ProfessionnelFactory;
use App\Factory\SeanceFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création de 20 patients
        $patients = PatientFactory::createMany(20);

        // Création de 20 professionnels
        $professionnels = ProfessionnelFactory::createMany(20);

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

        // Création d'un compte patient de test
        $patient = PatientFactory::createOne([
            'login' => 'patient',
        ]);

        // Associer des données existantes au compte patient
        SeanceFactory::createMany(2, function () use ($patient, $professionnels) {
            return [
                'patient' => $patient,
                'professionnel' => $professionnels[array_rand($professionnels)],
            ];
        });

        // Création d'un compte professionnel de test
        $professionnel = ProfessionnelFactory::createOne([
            'login' => 'pro',
        ]);

        // Associer des données existantes au compte professionnel
        SeanceFactory::createOne([
            'professionnel' => $professionnel,
            'patient' => $patients[array_rand($patients)],
        ]);

        // Création d'un compte admin de test
        $admin = AdminFactory::createOne([
            'login' => 'admin',
        ]);

    }
}

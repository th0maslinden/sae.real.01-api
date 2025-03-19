<?php

namespace App\Tests\Api\Seance;

use App\Entity\Patient;
use App\Entity\Professionnel;
use App\Entity\Seance;
use App\Factory\PatientFactory;
use App\Factory\ProfessionnelFactory;
use App\Factory\SeanceFactory;
use App\Repository\PatientRepository;
use App\Repository\ProfessionnelRepository;
use App\Tests\Support\ApiTester;

class SeanceGetCest
{
    protected static function expectedProperties(): array
    {
        return [
            'id' => 'integer',
            'date' => 'string:date',
            'heureDebut' => 'string:date',
            'heureFin' => 'string:date',
            'note' => 'string',
            'raison' => 'string',
            'patient' => Patient::class,
            'professionnel' => Professionnel::class,
        ];
    }

    public function getSeanceDetail(ApiTester $I): void
    {
        // 1. 'Arrange'
        $seanceData = [
            'id' => 1,
            'date' => new \DateTimeImmutable('01/02/25'),
            'heureDebut' => new \DateTimeImmutable('01/02/25 10:00:00'),
            'heureFin' => new \DateTimeImmutable('01/02/25 15:00:00'),
            'note' => 'ceci est une note',
            'raison' => 'ceci est la raison',
            'patient' => PatientRepository::class->findOneById(1),
            'professionnel' => ProfessionnelRepository::class->findOneById(1),
        ];
        PatientFactory::createOne()->_real();
        ProfessionnelFactory::createOne()->_real();
        SeanceFactory::createOne($seanceData)->_real();

        $I->sendGet('/api/seance/1');

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Seance::class, '/api/seance/1');

        $seanceData['date'] = $seanceData['date']->format(\DateTimeInterface::W3C);
        $seanceData['heureDebut'] = $seanceData['heureDebut']->format(\DateTimeInterface::W3C);
        $seanceData['heureFin'] = $seanceData['heureFin']->format(\DateTimeInterface::W3C);
        $I->seeResponseIsAnItem(self::expectedProperties(), $seanceData);
    }
}

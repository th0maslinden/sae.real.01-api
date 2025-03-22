<?php

namespace App\Tests\Api\Patient;

use App\Entity\Patient;
use App\Factory\PatientFactory;
use App\Factory\SeanceFactory;
use App\Tests\Support\ApiTester;

class PatientGetCest
{
    protected static function expectedProperties(): array
    {
        return [
            '@id' => 'string',
            '@type' => 'string',
            'nom' => 'string',
            'prenom' => 'string',
            'pathologie' => 'string',
            'seances' => 'array',
        ];
    }

    public function getPatientLogDetail(ApiTester $I): void
    {
        $seance1 = SeanceFactory::createOne();
        $seance2 = SeanceFactory::createOne();

        $patientData = [
            'nom' => 'Moreno',
            'prenom' => 'Olivier',
            'pathologie' => 'pathologie',
            'seances' => [$seance1, $seance2],
        ];

        $Patient = PatientFactory::createOne($patientData)->_real();
        $I->amLoggedInAs($Patient);

        $I->sendGet("/api/patients/1");
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Patient::class, "/api/patients/1");

        $expectedResponseData = [
            '@id' => "/api/patients/1",
            '@type' => 'Patient',
            'nom' => 'Moreno',
            'prenom' => 'Olivier',
            'pathologie' => 'pathologie',
            'seances' => ["/api/seances/1", "/api/seances/2"],
        ];

        $I->seeResponseIsAnItem(self::expectedProperties(), json_decode($I->grabResponse(), true));
    }
}

<?php

namespace App\Tests\Api\Patient;

use App\Entity\Patient;
use App\Factory\PatientFactory;
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
        $patientData = [
            'nom' => 'Moreno',
            'prenom' => 'Olivier',
        ];

        $Patient = PatientFactory::createOne($patientData)->_real();
        $I->amLoggedInAs($Patient);

        $I->sendGet("/api/patients/1");
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Patient::class, "/api/patients/1");

        $I->seeResponseIsAnItem(self::expectedProperties(), $patientData);
    }

    public function getOthersPatientWhenLog(ApiTester $I)
    {
        $patientData = [
            'nom' => 'Moreno',
            'prenom' => 'Olivier',
        ];

        $Patient = PatientFactory::createOne()->_real();
        $I->amLoggedInAs($Patient);
        PatientFactory::createOne($patientData);

        $I->sendGet("/api/patients/2");

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Patient::class, "/api/patients/2");
        $I->seeResponseIsAnItem(self::expectedProperties(), $patientData);
    }
}

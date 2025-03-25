<?php

namespace App\Tests\Api\Patient;

use App\Entity\Patient;
use App\Factory\PatientFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PatientPatchCest
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

    public function anonymousPatientPatch(ApiTester $I): void
    {
        PatientFactory::createOne();
        $I->sendPatch('/api/patients/1');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function authenticatedPatientForbiddenPatchOtherPatient(ApiTester $I): void
    {
        $Patient = PatientFactory::createOne()->_real();
        PatientFactory::createOne();
        $I->amLoggedInAs($Patient);

        $I->sendPatch('/api/users/2');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

//    public function authenticatedPatientCanPatchOwnData(ApiTester $I): void
//    {
//        $patientData = [
//            'nom' => 'Moreno',
//            'prenom' => 'Olivier',
//        ];
//
//        $Patient = PatientFactory::createOne($patientData)->_real();
//        $I->amLoggedInAs($Patient);
//
//        $dataPatch = [
//            'nom' => 'Jean',
//            'prenom' => 'Martin',
//        ];
//
//        $I->sendPatch('/api/users/1', $dataPatch);
//
//        $I->seeResponseCodeIsSuccessful();
//        $I->seeResponseIsJson();
//        $I->seeResponseIsAnEntity(Patient::class, '/api/users/1');
//        $I->seeResponseIsAnItem(self::expectedProperties(), $dataPatch);
//    }
}

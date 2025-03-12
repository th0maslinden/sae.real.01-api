<?php

namespace App\Factory;

use AllowDynamicProperties;
use App\Entity\Patient;
use App\Entity\User;
use App\Repository\PatientRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AllowDynamicProperties] final class PatientFactory extends PersistentProxyObjectFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->transliterator = \Transliterator::create('Latin-ASCII');
    }

    public static function class(): string
    {
        return Patient::class;
    }

    protected function normalizeName(string $name): string
    {
        $normalized = $this->transliterator->transliterate($name);
        $normalized = preg_replace('/[^a-zA-Z0-9]+/', '-', $normalized);

        return $normalized;
    }

    protected function defaults(): array|callable
    {
        $pathologies = [
            'Arthrose',
            'Spondylarthrite ankylosante',
            'Syndrome du canal carpien',
            'Tendinopathies',
            'Sclérose en plaques',
            'Maladie de Parkinson',
            'Syndrome de Guillain-Barré',
            'Paralysie faciale',
            'Rééducation post-infarctus',
            'Insuffisance cardiaque',
            'Artérite oblitérante des membres inférieurs',
            'Broncho-pneumopathie chronique obstructive (BPCO)',
            'Fibrose pulmonaire',
            "Syndrome d'apnées du sommeil",
            'Polyarthrite rhumatoïde',
            'Lupus érythémateux disséminé',
            'Fibromyalgie',
            'Rééducation après chirurgie de la hanche ou du genou',
            'Rééducation après chirurgie du rachis',
            'Paralysie cérébrale',
            'Scoliose idiopathique',
            'Syndrome de pied bot',
            'Ostéoporose avec fractures',
            "Syndrome de déconditionnement à l'effort",
            'Neuropathies diabétiques',
            'Syndrome du défilé thoracique',
            'Algoneurodystrophie',
            'Syndrome douloureux régional complexe',
            'Syndrome post-commotionnel',
            'Rééducation après traumatisme crânien sévère',
            'Rééducation post-cancer du sein',
            'Rééducation après chirurgie pour tumeur cérébrale',
        ];

        $firstName = self::faker()->firstName();
        $lastName = self::faker()->lastName();
        $normalizedFirstname = $this->normalizeName($firstName);
        $normalizedLastname = $this->normalizeName($lastName);
        $login = strtolower($normalizedFirstname).'.'.strtolower($normalizedLastname).self::faker()->numberBetween(1, 999);
        $password = 'password123';

        return [
            'login' => $login,
            'nom' => $normalizedLastname,
            'prenom' => $normalizedFirstname,
            'pathologie' => self::faker()->randomElement($pathologies),
            'password' => $this->passwordHasher->hashPassword(new User(), $password),
            'roles' => ['ROLE_PATIENT'],
            'email' => "$login@example.com",
        ];
    }
}

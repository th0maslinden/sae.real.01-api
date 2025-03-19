<?php

namespace App\Factory;

use App\Entity\Professionnel;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

#[\AllowDynamicProperties] final class ProfessionnelFactory extends PersistentProxyObjectFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->transliterator = \Transliterator::create('Latin-ASCII');
    }

    public static function class(): string
    {
        return Professionnel::class;
    }

    protected function normalizeName(string $name): string
    {
        $normalized = $this->transliterator->transliterate($name);
        $normalized = preg_replace('/[^a-zA-Z0-9]+/', '-', $normalized);

        return $normalized;
    }

    protected function defaults(): array|callable
    {
        $specialitesReeducation = [
            'Rhumatologue',
            'Neurologue',
            'Cardiologue',
            'Pneumologue',
            'Kinésithérapeute',
            'Orthopédiste',
            'Médecin de réadaptation',
            'Gériatre',
            'Pédiatre',
            'Chirurgien orthopédiste',
            'Neurochirurgien',
            'Médecin de la douleur',
            'Oncologue',
            'Endocrinologue',
            'Psychiatre',
            'Orthophoniste',
            'Ergothérapeute',
            'Podologue',
            'Médecin du sport',
            'Médecin généraliste',
        ];

        $firstName = self::faker()->firstName();
        $lastName = self::faker()->lastName();
        $normalizedFirstname = $this->normalizeName($firstName);
        $normalizedLastname = $this->normalizeName($lastName);
        $login = strtolower($normalizedFirstname).'.'.strtolower($normalizedLastname).self::faker()->numberBetween(1, 999);
        $password = 'test';

        return [
            'login' => $login,
            'nom' => $normalizedLastname,
            'prenom' => $normalizedFirstname,
            'specialite' => self::faker()->randomElement($specialitesReeducation),
            'password' => $this->passwordHasher->hashPassword(new User(), $password),
            'roles' => ['ROLE_PROFESSIONNEL'],
            'email' => "$login@example.com",
        ];
    }
}

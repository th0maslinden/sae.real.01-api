<?php

namespace App\Factory;

use App\Entity\Admin;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

#[\AllowDynamicProperties] final class AdminFactory extends PersistentProxyObjectFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->transliterator = \Transliterator::create('Latin-ASCII');
    }

    public static function class(): string
    {
        return Admin::class;
    }

    protected function normalizeName(string $name): string
    {
        $normalized = $this->transliterator->transliterate($name);
        $normalized = preg_replace('/[^a-zA-Z0-9]+/', '-', $normalized);

        return $normalized;
    }

    protected function defaults(): array|callable
    {
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
            'password' => $this->passwordHasher->hashPassword(new User(), $password),
            'email' => "$login@example.com",
            'typeAdmin' => self::faker()->randomElement(['ADMIN_INF', 'ADMIN_SEC']),
            'roles' => ['ROLE_ADMIN'],
        ];
    }
}

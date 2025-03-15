<?php

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends PersistentProxyObjectFactory<User>
 *
 * @method        User|Proxy create(array|callable $attributes = [])
 * @method static User|Proxy createOne(array $attributes = [])
 * @method static User|Proxy find(object|array|mixed $criteria)
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy first(string $sortedField = 'id')
 * @method static User|Proxy last(string $sortedField = 'id')
 * @method static User|Proxy random(array $attributes = [])
 * @method static User|Proxy randomOrCreate(array $attributes = [])
 * @method static UserRepository|ProxyRepositoryDecorator repository()
 * @method static User[]|Proxy[] all()
 * @method static User[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static User[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static User[]|Proxy[] findBy(array $attributes)
 * @method static User[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static User[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public static function class(): string
    {
        return User::class;
    }

    protected function defaults(): array|callable
    {
        $login = self::faker()->userName();
        $password = 'test';

        return [
            'login' => $login,
            'password' => $this->passwordHasher->hashPassword(new User(), $password),
            'roles' => ['ROLE_USER'],
            'email' => self::faker()->email(),
        ];
    }
}

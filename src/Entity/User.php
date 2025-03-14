<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'dtype', type: 'string')]
#[ORM\DiscriminatorMap([
    'user' => User::class,
    'patient' => Patient::class,
    'professionnel' => Professionnel::class,
    'admin' => Admin::class,
])]
#[ORM\Table(name: 'user')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_LOGIN', fields: ['login'])]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['user:read']]),
        new Post(
            normalizationContext: ['groups' => ['user:read']],
            denormalizationContext: ['groups' => ['user:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Put(
            normalizationContext: ['groups' => ['user:read']],
            denormalizationContext: ['groups' => ['user:write']],
            security: "is_granted('ROLE_ADMIN') or (object == user and is_granted('ROLE_PROFESSIONNEL'))"
        ),
        new Patch(
            normalizationContext: ['groups' => ['user:read']],
            denormalizationContext: ['groups' => ['user:write']],
            security: "is_granted('ROLE_ADMIN') or (object == user and is_granted('ROLE_PROFESSIONNEL'))"
        ),
        new Delete(security: "is_granted('ROLE_ADMIN')")
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    protected ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['user:read', 'user:write'])]
    protected ?string $login = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    protected array $roles = [];

    #[ORM\Column]
    #[Groups(['user:write'])]
    protected ?string $password = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    protected $avatar = null;

    #[ORM\Column(length: 100)]
    #[Groups(['user:read', 'user:write'])]
    protected ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void {}

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): static
    {
        $this->avatar = $avatar;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }
}
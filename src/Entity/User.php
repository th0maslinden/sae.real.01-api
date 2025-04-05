<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\OpenApi\Model\Operation;
use App\Controller\GetAvatarController;
use App\Repository\UserRepository;
use App\State\MeProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_LOGIN', fields: ['login'])]
#[ApiResource(
    operations: [
        new Get(),
        new Get(
            uriTemplate: '/me',
            openapi: new Operation(
                summary: 'Retrieves the connected user',
                description: 'Retrieves the connected user',
                security: ["is_granted('ROLE_USER')"],
            ),
            normalizationContext: ['groups' => ['User_read', 'User_me']],
            provider: MeProvider::class
        ),
        new Get(
            uriTemplate: '/users/{id}/avatar',
            formats: [
                'png' => 'image/png',
            ],
            controller: GetAvatarController::class,
            openapi: new Operation(
                responses: [
                    '200' => [
                        'description' => 'The user avatar',
                        'content' => [
                            'image/png' => [
                                'schema' => [
                                    'type' => 'string',
                                    'format' => 'binary',
                                ],
                            ],
                        ],
                    ],
                    '404' => [
                        'description' => 'User does not exist',
                    ],
                ],
                summary: 'Retrieves a user avatar',
                description: 'Retrieves the PNG image corresponding to a user avatar',
            ),
        ),
        new Patch(
            normalizationContext: ['groups' => ['User_read', 'User_me']],
            denormalizationContext: ['groups' => ['User_write']],
            security: "is_granted('ROLE_USER') and object == user"
        ),
    ],
    normalizationContext: ['groups' => ['User_read']]
)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'dtype', type: 'string')]
#[ORM\DiscriminatorMap([
    'user' => User::class,
    'patient' => Patient::class,
    'professionnel' => Professionnel::class,
    'admin' => Admin::class,
])]
#[UniqueEntity('login')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['User_read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['User_read', 'User_write'])]
    #[Assert\Regex(
        pattern: '/[<>\"&]/',
        message: 'Le nom contient des caractères interdits : <, >, & et "',
        match: false
    )]
    #[ApiProperty(
        description: 'Login de notre utilisateur.',
        openapiContext: [
            'type' => 'string',
            'example' => 'user1',
        ]
    )]
    private ?string $login = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(['user:read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['User_write'])]
    private ?string $password = null;

    #[ORM\Column(length: 30)]
    #[Groups(['User_read', 'User_write', 'search_result', 'seance:read'])]
    #[Assert\Regex(
        pattern: '/[<>\"&]/',
        message: 'Le nom contient des caractères interdits : <, >, & et "',
        match: false
    )]
    #[ApiProperty(
        description: 'Prénom de notre utilisateur.',
        openapiContext: [
            'type' => 'string',
            'example' => 'Solène',
        ]
    )]
    private ?string $firstname = null;

    #[ORM\Column(length: 40)]
    #[Groups(['User_read', 'User_write', 'search_result', 'seance:read'])]
    #[Assert\Regex(
        pattern: '/[<>\"&]/',
        message: 'Le nom contient des caractères interdits : <, >, & et "',
        match: false
    )]
    #[ApiProperty(
        description: 'Nom de famille de notre utilisateur.',
        openapiContext: [
            'type' => 'string',
            'example' => 'Depret--Masson',
        ]
    )]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $avatar;

    #[ORM\Column(length: 100)]
    #[Groups(['User_write', 'User_me', 'search_result'])]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

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

    public function __toString(): string
    {
        return $this->login;
    }
}

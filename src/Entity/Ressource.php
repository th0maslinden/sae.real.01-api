<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\RessourceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['ressource:read']]),
        new Post(
            normalizationContext: ['groups' => ['ressource:read']],
            denormalizationContext: ['groups' => ['ressource:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Put(
            normalizationContext: ['groups' => ['ressource:read']],
            denormalizationContext: ['groups' => ['ressource:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            normalizationContext: ['groups' => ['ressource:read']],
            denormalizationContext: ['groups' => ['ressource:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Delete(security: "is_granted('ROLE_ADMIN')")
    ]
)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ressource:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    #[Groups(['ressource:read', 'ressource:write'])]
    private ?string $nom = null;

    #[ORM\Column(length: 60, nullable: true)]
    #[Groups(['ressource:read', 'ressource:write'])]

    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }
}

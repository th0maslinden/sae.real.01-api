<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['admin:read', 'User_read']]),
        new Put(
            normalizationContext: ['groups' => ['admin:read']],
            denormalizationContext: ['groups' => ['admin:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            normalizationContext: ['groups' => ['admin:read']],
            denormalizationContext: ['groups' => ['admin:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
    ]
)]
class Admin extends User
{
    #[ORM\Column(length: 20)]
    #[Groups(['admin:read', 'admin:write'])]
    private ?string $typeAdmin = null;

    public function getTypeAdmin(): ?string
    {
        return $this->typeAdmin;
    }

    public function setTypeAdmin(string $typeAdmin): static
    {
        $this->typeAdmin = $typeAdmin;

        return $this;
    }
}

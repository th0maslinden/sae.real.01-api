<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use App\Repository\ProfessionnelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProfessionnelRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['professionnel:read']]),
        new Put(
            normalizationContext: ['groups' => ['professionnel:read']],
            denormalizationContext: ['groups' => ['professionnel:write']],
            security: "is_granted('ROLE_ADMIN') or (object == user and is_granted('ROLE_PROFESSIONNEL'))"
        ),
        new Patch(
            normalizationContext: ['groups' => ['professionnel:read']],
            denormalizationContext: ['groups' => ['professionnel:write']],
            security: "is_granted('ROLE_ADMIN') or (object == user and is_granted('ROLE_PROFESSIONNEL'))"
        ),
    ]
)]
class Professionnel extends User
{
    #[ORM\Column(length: 40)]
    #[Groups(['professionnel:read', 'professionnel:write'])]
    private ?string $specialite = null;

    /**
     * @var Collection<int, Seance>
     */
    #[ORM\OneToMany(mappedBy: 'professionnel', targetEntity: Seance::class)]
    #[Groups(['professionnel:read'])]
    private Collection $seances;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): static
    {
        if (!$this->seances->contains($seance)) {
            $this->seances->add($seance);
            $seance->setProfessionnel($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            if ($seance->getProfessionnel() === $this) {
                $seance->setProfessionnel(null);
            }
        }

        return $this;
    }
}

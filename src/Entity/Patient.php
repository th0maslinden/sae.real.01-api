<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['patient:read']]),
        new Put(
            normalizationContext: ['groups' => ['patient:read']],
            denormalizationContext: ['groups' => ['patient:write']],
            security: "is_granted('ROLE_ADMIN') or (object == user and is_granted('ROLE_PATIENT'))"
        ),
        new Patch(
            normalizationContext: ['groups' => ['patient:read']],
            denormalizationContext: ['groups' => ['patient:write']],
            security: "is_granted('ROLE_ADMIN') or (object == user and is_granted('ROLE_PATIENT'))"
        ),
    ]
)]
class Patient extends User
{
    #[ORM\Column(length: 40)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $nom = null;

    #[ORM\Column(length: 40)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $prenom = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $pathologie = null;

    /**
     * @var Collection<int, Seance>
     */
    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Seance::class)]
    #[Groups(['patient:read'])]
    private Collection $seances;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPathologie(): ?string
    {
        return $this->pathologie;
    }

    public function setPathologie(?string $pathologie): static
    {
        $this->pathologie = $pathologie;

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
            $seance->setPatient($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            if ($seance->getPatient() === $this) {
                $seance->setPatient(null);
            }
        }

        return $this;
    }
}

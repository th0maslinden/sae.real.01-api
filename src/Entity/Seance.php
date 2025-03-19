<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\SeanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['seance:read']]),
        new Post(
            normalizationContext: ['groups' => ['seance:read']],
            denormalizationContext: ['groups' => ['seance:write']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_PROFESSIONNEL')"
        ),
        new Put(
            normalizationContext: ['groups' => ['seance:read']],
            denormalizationContext: ['groups' => ['seance:write']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_PROFESSIONNEL') or (object.getPatient() == user)"
        ),
        new Patch(
            normalizationContext: ['groups' => ['seance:read']],
            denormalizationContext: ['groups' => ['seance:write']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_PROFESSIONNEL') or (object.getPatient() == user)"
        ),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ]
)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['seance:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['seance:read', 'seance:write'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups(['seance:read', 'seance:write'])]
    private ?\DateTimeInterface $heureDebut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups(['seance:read', 'seance:write'])]
    private ?\DateTimeInterface $heureFin = null;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Groups(['seance:read', 'seance:write'])]
    private ?string $note = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['seance:read', 'seance:write'])]
    private ?string $raison = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    #[Groups(['seance:read', 'seance:write'])]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    #[Groups(['seance:read', 'seance:write'])]
    private ?Professionnel $professionnel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(?\DateTimeInterface $heureDebut): static
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heureFin;
    }

    public function setHeureFin(?\DateTimeInterface $heureFin): static
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(?string $raison): static
    {
        $this->raison = $raison;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getProfessionnel(): ?Professionnel
    {
        return $this->professionnel;
    }

    public function setProfessionnel(?Professionnel $professionnel): static
    {
        $this->professionnel = $professionnel;

        return $this;
    }
}

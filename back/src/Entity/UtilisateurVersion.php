<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\UtilisateurVersionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UtilisateurVersionRepository::class)]
#[ORM\UniqueConstraint(name: 'uniq_utilisateur_version', fields: ['utilisateur', 'version'])]
class UtilisateurVersion
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateObtention = null;

    #[ORM\Column(nullable: true)]
    private ?int $iterationAvantObtention = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\ManyToOne(inversedBy: 'utilisateurVersions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'utilisateurVersions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Version $version = null;

    #[ORM\ManyToOne(inversedBy: 'utilisateurVersions')]
    private ?Tag $methodeObtention = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateObtention(): ?\DateTimeInterface
    {
        return $this->dateObtention;
    }

    public function setDateObtention(?\DateTimeInterface $dateObtention): static
    {
        $this->dateObtention = $dateObtention;

        return $this;
    }

    public function getIterationAvantObtention(): ?int
    {
        return $this->iterationAvantObtention;
    }

    public function setIterationAvantObtention(?int $iterationAvantObtention): static
    {
        $this->iterationAvantObtention = $iterationAvantObtention;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getVersion(): ?Version
    {
        return $this->version;
    }

    public function setVersion(?Version $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getMethodeObtention(): ?Tag
    {
        return $this->methodeObtention;
    }

    public function setMethodeObtention(?Tag $methodeObtention): static
    {
        $this->methodeObtention = $methodeObtention;

        return $this;
    }
}

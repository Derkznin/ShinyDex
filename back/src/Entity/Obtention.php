<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\ObtentionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ObtentionRepository::class)]
class Obtention
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

    #[ORM\ManyToOne(inversedBy: 'obtentions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'obtentions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Version $version = null;

    #[ORM\ManyToOne(inversedBy: 'obtentions')]
    private ?Tag $methodeObtention = null;

    /**
     * @var Collection<int, ObtentionTag>
     */
    #[ORM\OneToMany(targetEntity: ObtentionTag::class, mappedBy: 'obtention', orphanRemoval: true)]
    private Collection $obtentionTags;

    public function __construct()
    {
        $this->obtentionTags = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, ObtentionTag>
     */
    public function getObtentionTags(): Collection
    {
        return $this->obtentionTags;
    }

    public function addObtentionTag(ObtentionTag $obtentionTag): static
    {
        if (!$this->obtentionTags->contains($obtentionTag)) {
            $this->obtentionTags->add($obtentionTag);
            $obtentionTag->setObtention($this);
        }

        return $this;
    }

    public function removeObtentionTag(ObtentionTag $obtentionTag): static
    {
        if ($this->obtentionTags->removeElement($obtentionTag)) {
            // set the owning side to null (unless already changed)
            if ($obtentionTag->getObtention() === $this) {
                $obtentionTag->setObtention(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return ($this->utilisateur ? (string) $this->utilisateur : '').' - '.($this->version ? (string) $this->version : '');
    }
}

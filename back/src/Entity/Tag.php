<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Tag
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $categorie = null;

    /**
     * @var Collection<int, Obtention>
     */
    #[ORM\OneToMany(targetEntity: Obtention::class, mappedBy: 'methodeObtention')]
    private Collection $obtentions;

    /**
     * @var Collection<int, ObtentionTag>
     */
    #[ORM\OneToMany(targetEntity: ObtentionTag::class, mappedBy: 'tag', orphanRemoval: true)]
    private Collection $obtentionTags;

    public function __construct()
    {
        $this->obtentions = new ArrayCollection();
        $this->obtentionTags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Obtention>
     */
    public function getObtentions(): Collection
    {
        return $this->obtentions;
    }

    public function addObtention(Obtention $obtention): static
    {
        if (!$this->obtentions->contains($obtention)) {
            $this->obtentions->add($obtention);
            $obtention->setMethodeObtention($this);
        }

        return $this;
    }

    public function removeObtention(Obtention $obtention): static
    {
        if ($this->obtentions->removeElement($obtention)) {
            // set the owning side to null (unless already changed)
            if ($obtention->getMethodeObtention() === $this) {
                $obtention->setMethodeObtention(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return ($this->categorie ?? '').' - '.($this->nom ?? '');
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
            $obtentionTag->setTag($this);
        }

        return $this;
    }

    public function removeObtentionTag(ObtentionTag $obtentionTag): static
    {
        if ($this->obtentionTags->removeElement($obtentionTag)) {
            // set the owning side to null (unless already changed)
            if ($obtentionTag->getTag() === $this) {
                $obtentionTag->setTag(null);
            }
        }

        return $this;
    }
}

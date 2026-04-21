<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @var Collection<int, UtilisateurVersion>
     */
    #[ORM\OneToMany(targetEntity: UtilisateurVersion::class, mappedBy: 'methodeObtention')]
    private Collection $utilisateurVersions;

    public function __construct()
    {
        $this->utilisateurVersions = new ArrayCollection();
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
     * @return Collection<int, UtilisateurVersion>
     */
    public function getUtilisateurVersions(): Collection
    {
        return $this->utilisateurVersions;
    }

    public function addUtilisateurVersion(UtilisateurVersion $utilisateurVersion): static
    {
        if (!$this->utilisateurVersions->contains($utilisateurVersion)) {
            $this->utilisateurVersions->add($utilisateurVersion);
            $utilisateurVersion->setMethodeObtention($this);
        }

        return $this;
    }

    public function removeUtilisateurVersion(UtilisateurVersion $utilisateurVersion): static
    {
        if ($this->utilisateurVersions->removeElement($utilisateurVersion)) {
            // set the owning side to null (unless already changed)
            if ($utilisateurVersion->getMethodeObtention() === $this) {
                $utilisateurVersion->setMethodeObtention(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->categorie.' - '.$this->nom;
    }
}

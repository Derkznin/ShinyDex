<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\VersionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VersionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Version
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomFichierImage = null;

    #[ORM\ManyToOne(inversedBy: 'versions')]
    #[ORM\JoinColumn(name: 'numero_pokemon', referencedColumnName: 'numero', nullable: false)]
    private ?Pokemon $pokemon = null;

    /**
     * @var Collection<int, UtilisateurVersion>
     */
    #[ORM\OneToMany(targetEntity: UtilisateurVersion::class, mappedBy: 'version')]
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

    public function getNomFichierImage(): ?string
    {
        return $this->nomFichierImage;
    }

    public function setNomFichierImage(?string $nomFichierImage): static
    {
        $this->nomFichierImage = $nomFichierImage;

        return $this;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): static
    {
        $this->pokemon = $pokemon;

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
            $utilisateurVersion->setVersion($this);
        }

        return $this;
    }

    public function removeUtilisateurVersion(UtilisateurVersion $utilisateurVersion): static
    {
        if ($this->utilisateurVersions->removeElement($utilisateurVersion)) {
            // set the owning side to null (unless already changed)
            if ($utilisateurVersion->getVersion() === $this) {
                $utilisateurVersion->setVersion(null);
            }
        }

        return $this;
    }
}

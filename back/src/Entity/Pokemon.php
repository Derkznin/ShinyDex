<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Pokemon
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column]
    private ?int $numero = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Version>
     */
    #[ORM\OneToMany(targetEntity: Version::class, mappedBy: 'pokemon')]
    private Collection $versions;

    public function __construct()
    {
        $this->versions = new ArrayCollection();
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(?int $numero): static
    {
        $this->numero = $numero;

        return $this;
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

    /**
     * @return Collection<int, Version>
     */
    public function getVersions(): Collection
    {
        return $this->versions;
    }

    public function addVersion(Version $version): static
    {
        if (!$this->versions->contains($version)) {
            $this->versions->add($version);
            $version->setPokemon($this);
        }

        return $this;
    }

    public function removeVersion(Version $version): static
    {
        if ($this->versions->removeElement($version)) {
            // set the owning side to null (unless already changed)
            if ($version->getPokemon() === $this) {
                $version->setPokemon(null);
            }
        }

        return $this;
    }
}

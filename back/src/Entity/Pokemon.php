<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
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

    /**
     * @var Collection<int, PokemonFavori>
     */
    #[ORM\OneToMany(targetEntity: PokemonFavori::class, mappedBy: 'pokemon', orphanRemoval: true)]
    private Collection $pokemonFavoris;

    public function __construct()
    {
        $this->versions = new ArrayCollection();
        $this->pokemonFavoris = new ArrayCollection();
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

    public function __toString(): string
    {
        return $this->numero.' - '.$this->nom;
    }

    /**
     * @return Collection<int, PokemonFavori>
     */
    public function getPokemonFavoris(): Collection
    {
        return $this->pokemonFavoris;
    }

    public function addPokemonFavori(PokemonFavori $pokemonFavori): static
    {
        if (!$this->pokemonFavoris->contains($pokemonFavori)) {
            $this->pokemonFavoris->add($pokemonFavori);
            $pokemonFavori->setPokemon($this);
        }

        return $this;
    }

    public function removePokemonFavori(PokemonFavori $pokemonFavori): static
    {
        if ($this->pokemonFavoris->removeElement($pokemonFavori)) {
            // set the owning side to null (unless already changed)
            if ($pokemonFavori->getPokemon() === $this) {
                $pokemonFavori->setPokemon(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\PokemonFavoriRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PokemonFavoriRepository::class)]
#[ORM\UniqueConstraint(name: 'uniq_utilisateur_pokemon_favori', fields: ['utilisateur', 'pokemon'])]
class PokemonFavori
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pokemonFavoris')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'pokemonFavoris')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'numero')]
    private ?Pokemon $pokemon = null;

    #[ORM\ManyToOne(inversedBy: 'pokemonFavoris')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Version $version = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): static
    {
        $this->pokemon = $pokemon;

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

    public function __toString(): string
    {
        return ($this->utilisateur ? (string) $this->utilisateur : '').' - '.($this->pokemon ? (string) $this->pokemon : '');
    }
}

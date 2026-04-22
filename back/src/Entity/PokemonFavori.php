<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Contract\UserOwnedInterface;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\PokemonFavoriRepository;
use App\State\ObtentionProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['pokemon_favori:read']],
    denormalizationContext: ['groups' => ['pokemon_favori:write']],
    operations: [
        new GetCollection(security: 'is_granted("ROLE_USER")'),
        new Get(security: 'is_granted("ROLE_USER")'),
        new Post(security: 'is_granted("ROLE_USER")', processor: ObtentionProcessor::class),
        new Patch(security: 'is_granted("ROLE_USER")'),
        new Delete(security: 'is_granted("ROLE_USER")'),
    ]
)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PokemonFavoriRepository::class)]
#[ORM\UniqueConstraint(name: 'uniq_utilisateur_pokemon_favori', fields: ['utilisateur', 'pokemon'])]
class PokemonFavori implements UserOwnedInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pokemon_favori:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pokemonFavoris')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[Groups(['pokemon_favori:read', 'pokemon_favori:write'])]
    #[ORM\ManyToOne(inversedBy: 'pokemonFavoris')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'numero')]
    private ?Pokemon $pokemon = null;

    #[Groups(['pokemon_favori:read', 'pokemon_favori:write'])]
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

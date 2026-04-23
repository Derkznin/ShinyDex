<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\VersionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(security: 'is_granted("ROLE_ADMIN")'),
        new Patch(security: 'is_granted("ROLE_ADMIN")'),
        new Delete(security: 'is_granted("ROLE_ADMIN")'),
    ]
)]
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
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    // Regex stricte : nom de fichier simple, sans chemin ni caractères dangereux
    // Bloque : ../etc/passwd, /absolute/path, null bytes, etc.
    #[Assert\Length(max: 255)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9_\-\.]+\.(png|jpg|jpeg|webp)$/',
        message: 'Nom de fichier invalide. Format attendu : lettres, chiffres, tirets, underscores, extension png/jpg/jpeg/webp.'
    )]
    private ?string $nomFichierImage = null;

    #[ORM\ManyToOne(inversedBy: 'versions')]
    #[ORM\JoinColumn(name: 'numero_pokemon', referencedColumnName: 'numero', nullable: false)]
    private ?Pokemon $pokemon = null;

    /**
     * @var Collection<int, Obtention>
     */
    #[ORM\OneToMany(targetEntity: Obtention::class, mappedBy: 'version')]
    private Collection $obtentions;

    /**
     * @var Collection<int, PokemonFavori>
     */
    #[ORM\OneToMany(targetEntity: PokemonFavori::class, mappedBy: 'version', orphanRemoval: true)]
    private Collection $pokemonFavoris;

    public function __construct()
    {
        $this->obtentions = new ArrayCollection();
        $this->pokemonFavoris = new ArrayCollection();
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
            $obtention->setVersion($this);
        }

        return $this;
    }

    public function removeObtention(Obtention $obtention): static
    {
        if ($this->obtentions->removeElement($obtention)) {
            // set the owning side to null (unless already changed)
            if ($obtention->getVersion() === $this) {
                $obtention->setVersion(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return ($this->pokemon ? (string) $this->pokemon : '').' - '.($this->nom ?? '');
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
            $pokemonFavori->setVersion($this);
        }

        return $this;
    }

    public function removePokemonFavori(PokemonFavori $pokemonFavori): static
    {
        if ($this->pokemonFavoris->removeElement($pokemonFavori)) {
            // set the owning side to null (unless already changed)
            if ($pokemonFavori->getVersion() === $this) {
                $pokemonFavori->setVersion(null);
            }
        }

        return $this;
    }
}
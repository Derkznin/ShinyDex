<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: false)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    /**
     * @var Collection<int, Obtention>
     */
    #[ORM\OneToMany(targetEntity: Obtention::class, mappedBy: 'utilisateur', orphanRemoval: true)]
    private Collection $obtentions;

    /**
     * @var Collection<int, PokemonFavori>
     */
    #[ORM\OneToMany(targetEntity: PokemonFavori::class, mappedBy: 'utilisateur', orphanRemoval: true)]
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

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
            $obtention->setUtilisateur($this);
        }

        return $this;
    }

    public function removeObtention(Obtention $obtention): static
    {
        if ($this->obtentions->removeElement($obtention)) {
            // set the owning side to null (unless already changed)
            if ($obtention->getUtilisateur() === $this) {
                $obtention->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->username ?? '';
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
            $pokemonFavori->setUtilisateur($this);
        }

        return $this;
    }

    public function removePokemonFavori(PokemonFavori $pokemonFavori): static
    {
        if ($this->pokemonFavoris->removeElement($pokemonFavori)) {
            // set the owning side to null (unless already changed)
            if ($pokemonFavori->getUtilisateur() === $this) {
                $pokemonFavori->setUtilisateur(null);
            }
        }

        return $this;
    }
}

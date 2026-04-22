<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\ObtentionTagRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    operations: [
        new GetCollection(security: 'is_granted("ROLE_USER")'),
        new Get(security: 'is_granted("ROLE_USER")'),
        new Post(security: 'is_granted("ROLE_USER")'),
        new Patch(security: 'is_granted("ROLE_USER")'),
        new Delete(security: 'is_granted("ROLE_USER")'),
    ]
)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ObtentionTagRepository::class)]
class ObtentionTag
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'obtentionTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Obtention $obtention = null;

    #[ORM\ManyToOne(inversedBy: 'obtentionTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tag $tag = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObtention(): ?Obtention
    {
        return $this->obtention;
    }

    public function setObtention(?Obtention $obtention): static
    {
        $this->obtention = $obtention;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    public function __toString(): string
    {
        return ($this->obtention ? (string) $this->obtention : '').' - '.($this->tag ? (string) $this->tag : '');
    }
}

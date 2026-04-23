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
use App\State\ObtentionTagProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['obtention_tag:read']],
    denormalizationContext: ['groups' => ['obtention_tag:write']],
    operations: [
        new GetCollection(security: 'is_granted("ROLE_USER")'),
        new Get(security: 'is_granted("ROLE_USER")'),
        new Post(security: 'is_granted("ROLE_USER")', processor: ObtentionTagProcessor::class),
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
    #[Groups(['obtention_tag:read'])]
    private ?int $id = null;

    // Pas de groupe read → pas de référence circulaire avec Obtention
    // Le client envoie l'IRI en write, API Platform résout automatiquement
    #[Groups(['obtention_tag:write'])]
    #[ORM\ManyToOne(inversedBy: 'obtentionTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Obtention $obtention = null;

    #[Groups(['obtention_tag:read', 'obtention_tag:write'])]
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

<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\TagVersionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(
    fields: ['version', 'tag'],
    message: 'Ce tag est déjà associé à cette version.'
)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(security: 'is_granted("ROLE_ADMIN")'),
        new Patch(security: 'is_granted("ROLE_ADMIN")'),
        new Delete(security: 'is_granted("ROLE_ADMIN")'),
    ]
)]
#[ORM\Entity(repositoryClass: TagVersionRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_version_tag', fields: ['version', 'tag'])]
class TagVersion
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_version', referencedColumnName: 'id', nullable: false)]
    private ?Version $version = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_tag', referencedColumnName: 'id', nullable: false)]
    private ?Tag $tag = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): static
    {
        $this->tag = $tag;

        return $this;
    }
}
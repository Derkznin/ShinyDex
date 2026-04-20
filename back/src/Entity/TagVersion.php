<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\TagVersionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagVersionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class TagVersion
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_version', referencedColumnName: 'id', nullable: false)]
    private ?Version $version = null;

    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_tag', referencedColumnName: 'id', nullable: false)]
    private ?Tag $tag = null;

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

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SourcesRepository")
 */
class Sources
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $source_name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceName(): ?string
    {
        return $this->source_name;
    }

    public function setSourceName(string $source_name): self
    {
        $this->source_name = $source_name;

        return $this;
    }
}

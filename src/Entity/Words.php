<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WordsRepository")
 */
class Words
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $word;

    /**
     * @ORM\Column(type="integer")
     */
    private $positive;

    /**
     * @ORM\Column(type="integer")
     */
    private $negative;

    /**
     * @ORM\Column(type="integer")
     */
    private $source;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWord(): ?string
    {
        return $this->word;
    }

    public function setWord(string $word): self
    {
        $this->word = $word;

        return $this;
    }

    public function getPositive(): ?int
    {
        return $this->positive;
    }

    public function setPositive(int $positive): self
    {
        $this->positive = $positive;

        return $this;
    }

    public function getNegative(): ?int
    {
        return $this->negative;
    }

    public function setNegative(int $negative): self
    {
        $this->negative = $negative;

        return $this;
    }

    public function getSource(): ?int
    {
        return $this->source;
    }

    public function setSource(int $source): self
    {
        $this->source = $source;

        return $this;
    }
}

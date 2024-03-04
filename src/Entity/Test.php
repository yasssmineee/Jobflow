<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Mime\Message;

#[ORM\Entity(repositoryClass: TestRepository::class)]
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    ##[Assert\NotBlank(message: "email ne doit pas etre vide ")]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: "Le type doit contenir que des caractères alphabétiques."
    )]
    private ?string $Type = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero(message: "Le score doit être positif ou nul")]
    private ?int $score = null;

    #[ORM\Column(length: 255)]
    #[Assert\PositiveOrZero(message: "La durée doit être positif ou nul")]
    private ?string $duree = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): static
    {
        $this->Type = $Type;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }
}

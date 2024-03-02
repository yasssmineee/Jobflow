<?php

namespace App\Entity;

use App\Repository\PostulerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostulerRepository::class)]
class Postuler
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $motivationText = null;

    #[ORM\Column(length: 255)]
    private ?string $cv = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;
    
    #[ORM\ManyToOne (inversedBy : 'Opportunites')]
    private ?Opportunite $idOpportunite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotivationText(): ?string
    {
        return $this->motivationText;
    }

    public function setMotivationText(string $motivationText): static
    {
        $this->motivationText = $motivationText;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(string $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getIdOpportunite(): ?Opportunite
    {
        return $this->idOpportunite;

    }

    public function setIdOpportunite(?Opportunite $idOpportunite): static
    {
        $this->idOpportunite = $idOpportunite;

        return $this;
    }
}
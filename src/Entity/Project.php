<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $prname = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $stdate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $enddate = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?Chat $idchat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrname(): ?string
    {
        return $this->prname;
    }

    public function setPrname(string $prname): static
    {
        $this->prname = $prname;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStdate(): ?\DateTimeInterface
    {
        return $this->stdate;
    }

    public function setStdate(\DateTimeInterface $stdate): static
    {
        $this->stdate = $stdate;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(\DateTimeInterface $enddate): static
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getIdchat(): ?chat
    {
        return $this->idchat;
    }

    public function setIdchat(?chat $idchat): static
    {
        $this->idchat = $idchat;

        return $this;
    }
}

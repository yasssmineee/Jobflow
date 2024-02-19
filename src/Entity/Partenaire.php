<?php

namespace App\Entity;

use App\Repository\PartenaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Mime\Message;

#[ORM\Entity(repositoryClass: PartenaireRepository::class)]
class Partenaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "nom ne doit pas etre vide ")]

    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $duree = null;

    #[ORM\Column(length: 255)]
    private ?string $descreption = null;

    #[ORM\ManyToMany(targetEntity: Societe::class)]
    private Collection $idsociete;

    public function __construct()
    {
        $this->idsociete = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getDescreption(): ?string
    {
        return $this->descreption;
    }

    public function setDescreption(string $descreption): static
    {
        $this->descreption = $descreption;

        return $this;
    }

    /**
     * @return Collection<int, societe>
     */
    public function getIdsociete(): Collection
    {
        return $this->idsociete;
    }

    public function addIdsociete(societe $idsociete): static
    {
        if (!$this->idsociete->contains($idsociete)) {
            $this->idsociete->add($idsociete);
        }

        return $this;
    }

    public function removeIdsociete(societe $idsociete): static
    {
        $this->idsociete->removeElement($idsociete);

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\SocieteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Mime\Message;

#[ORM\Entity(repositoryClass: SocieteRepository::class)]
class Societe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "nom ne doit pas etre vide ")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "localisation ne doit pas etre vide ")]
    private ?string $localisation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "descreption ne doit pas etre vide ")]
    private ?string $descreption = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "site web ne doit pas etre vide ")]
    private ?string $siteweb = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "numero tel ne doit pas etre des caracthere ")]
    private ?int $numtel = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "secteur ne doit pas etre vide ")]
    private ?string $secteur = null;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: "societe")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private $user;
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

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

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

    public function getSiteweb(): ?string
    {
        return $this->siteweb;
    }

    public function setSiteweb(string $siteweb): static
    {
        $this->siteweb = $siteweb;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): static
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    public function setSecteur(string $secteur): static
    {
        $this->secteur = $secteur;

        return $this;
    }
    public function __toString(): string
    {
        // Choose one of the properties as the string representation
        // For example, I'm using 'nom' property here
        return $this->nom;
    }
   
   

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
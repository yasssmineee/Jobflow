<?php

namespace App\Entity;

use App\Repository\OpportuniteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Mime\Message;

/**
 * @IgnoreAnnotation("ORM\Entity")
 */
#[ORM\Entity(repositoryClass:OpportuniteRepository::class)]
class Opportunite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]


    private ?int $id = null;
    #[Assert\NotBlank(message: "le champ ne doit pas etre vide ")]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: "Le nom doit contenir que des caractères alphabétiques."
    )]
    #[ORM\Column(length:255)]

    private ?string $nom = null;



    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: "La description doit contenir que des caractères alphabétiques."
    )]
    #[ORM\Column(length:255)]

    private ?string $descreption = null;


    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne (inversedBy : 'Opportunites')]
    private ?Test $idtest = null;

    #[ORM\Column]
    private ?bool $isFavorite = null;

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

    public function getDescreption(): ?string
    {
        return $this->descreption;
    }

    public function setDescreption(string $descreption): static
    {
        $this->descreption = $descreption;

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

    public function getIdtest(): ?Test
    {
        return $this->idtest;

    }

    public function setIdtest(?test $idtest): static
    {
        $this->idtest = $idtest;

        return $this;
    }

    public function isIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(bool $isFavorite): static
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }
}
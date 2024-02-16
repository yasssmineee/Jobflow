<?php

namespace App\Entity;

use App\Repository\SponsorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SponsorRepository::class)]
class Sponsor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide")]
    #[Assert\Type(type: "string", message: "Le nomdoit être une chaîne de caractères")]
    #[Assert\Regex(
    pattern: "/^[a-zA-Z\s]+$/",
    message: "Le nom ne peut contenir que des lettres et des espaces"
)]
#[Assert\Length(
    max: 255,
    maxMessage: "Lenom ne peut pas dépasser {{ limit }} caractères"
)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type ne peut pas être vide")]
    #[Assert\Type(type: "string", message: "Le type doit être une chaîne de caractères")]
    #[Assert\Regex(
    pattern: "/^[a-zA-Z\s]+$/",
    message: "Le type ne peut contenir que des lettres et des espaces"
)]
#[Assert\Length(
    max: 255,
    maxMessage: "Le type ne peut pas dépasser {{ limit }} caractères"
)]
    private ?string $type = null;

    #[ORM\ManyToMany(targetEntity: Evenement::class, inversedBy: 'sponsors')]
    private Collection $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): static
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): static
    {
        $this->evenements->removeElement($evenement);

        return $this;
    }
}
?>
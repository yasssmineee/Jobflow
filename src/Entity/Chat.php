<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $snid = null;

    #[ORM\Column]
    private ?int $rcid = null;

    #[ORM\OneToMany(targetEntity: project::class, mappedBy: 'idchat')]
    private Collection $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSnid(): ?int
    {
        return $this->snid;
    }

    public function setSnid(int $snid): static
    {
        $this->snid = $snid;

        return $this;
    }

    public function getRcid(): ?int
    {
        return $this->rcid;
    }

    public function setRcid(int $rcid): static
    {
        $this->rcid = $rcid;

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setIdchat($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getIdchat() === $this) {
                $project->setIdchat(null);
            }
        }

        return $this;
    }
}
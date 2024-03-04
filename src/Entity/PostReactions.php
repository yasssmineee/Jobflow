<?php

namespace App\Entity;

use App\Repository\PostReactionsRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Void_;

#[ORM\Entity(repositoryClass: PostReactionsRepository::class)]
class PostReactions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $likes = null;

    #[ORM\Column]
    private ?int $dislike = null;
    public function __construct( int $likes, int $dislikes)
    {
        
        $this->likes = $likes;
        $this->dislike = $dislikes;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): static
    {
        $this->likes = $likes;

        return $this;
    }

    public function getDislike(): ?int
    {
        return $this->dislike;
    }

    public function setDislike(int $dislike): static
    {
        $this->dislike = $dislike;

        return $this;
    }
}

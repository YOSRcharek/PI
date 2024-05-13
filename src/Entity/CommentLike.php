<?php

namespace App\Entity;

use App\Repository\CommentLikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentLikeRepository::class)]
class CommentLike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commentLikes', targetEntity: Comment::class )]
    private ?Comment $comment = null;

    #[ORM\ManyToOne(inversedBy: 'commentLikes', targetEntity: User::class )]
    private ?User $user = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $post_id = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
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

    public function getpost_id(): ?int
    {
        return $this->post_id;
    }

    public function setpost_id(?int $post_id): self
    {
        $this->post_id = $post_id;

        return $this;
    }
}

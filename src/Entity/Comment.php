<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message: 'Please insert your reply')]
    private ?string $contentcomment = null;

    #[ORM\Column]
    private ?int $upvotes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdatcomment = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $IDPost = null;

    #[ORM\ManyToOne(inversedBy: 'comments', targetEntity: User::class )]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $username;

    #[ORM\OneToMany(mappedBy: 'comment', targetEntity: CommentLike::class, orphanRemoval: true, cascade: ['remove'])]
    private Collection $commentLikes;

    public function __construct()
    {
        $this->commentLikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentcomment(): ?string
    {
        return $this->contentcomment;
    }

    public function setContentcomment(string $contentcomment): self
    {
        $this->contentcomment = $contentcomment;

        return $this;
    }

    public function getUpvotes(): ?int
    {
        return $this->upvotes;
    }

    public function setUpvotes(int $upvotes): self
    {
        $this->upvotes = $upvotes;

        return $this;
    }

    public function getCreatedatcomment(): ?\DateTimeInterface
    {
        return $this->createdatcomment;
    }

    public function setCreatedatcomment(\DateTimeInterface $createdatcomment): self
    {
        $this->createdatcomment = $createdatcomment;

        return $this;
    }

    public function getIDPost(): ?Post
    {
        return $this->IDPost;
    }

    public function setIDPost(?Post $IDPost): self
    {
        $this->IDPost = $IDPost;

        return $this;
    }

    public function getUsername(): ?User
    {
        return $this->username;
    }

    public function setUsername(?User $username): self
    {
        $this->username = $username;

        return $this;
    }
    public function getElapsedTime(): string
    {
        $now = new DateTime();
        $interval = $this->createdatcomment->diff($now);

        if ($interval->d > 0) {
            return $interval->format('%dd %hh ago');
        } elseif ($interval->h > 0) {
            return $interval->format('%hh %im ago');
        } elseif ($interval->i > 0) {
            return $interval->format('%im %ss ago');
        } else {
            return 'Just now';
        }
    }

    /**
     * @return Collection<int, CommentLike>
     */
    public function getCommentLikes(): Collection
    {
        return $this->commentLikes;
    }

    public function addCommentLike(CommentLike $commentLike): self
    {
        if (!$this->commentLikes->contains($commentLike)) {
            $this->commentLikes->add($commentLike);
            $commentLike->setComment($this);
        }

        return $this;
    }

    public function removeCommentLike(CommentLike $commentLike): self
    {
        if ($this->commentLikes->removeElement($commentLike)) {
            // set the owning side to null (unless already changed)
            if ($commentLike->getComment() === $this) {
                $commentLike->setComment(null);
            }
        }

        return $this;
    }
   
    public function isLikedByUser(User $user): bool
    {
        foreach ($this->commentLikes as $like) {
            if ($like->getUser() === $user) {
                return true;
            }
        }
        return false;
    }
}

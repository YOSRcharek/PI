<?php

namespace App\Entity;

use App\Repository\PostRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[Vich\Uploadable]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message: 'Please enter a title')]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message: 'Please enter post content')]
    private ?string $content = null;

    #[ORM\Column]
    private ?int $rating = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdat = null;

    #[ORM\ManyToOne(targetEntity: Usser::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(name: 'username_id', referencedColumnName: 'id')]
    private $username;


    
    #[ORM\OneToMany(mappedBy: 'IDPost', targetEntity: Comment::class, orphanRemoval: true, cascade: ['remove'])]
    private Collection $comments;




    #[ORM\Column]
    private ?bool $visible = null;

    #[ORM\Column(nullable: true)]
    private ?string $image = null;

    #[Vich\UploadableField(mapping: 'post', fileNameProperty: 'image')]
    #[Assert\NotBlank (message: 'Please insert an image')]
    private ?File $imageFile = null;



    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedat = null;

    #[ORM\Column(nullable: true)]
    private ?string $video = null;

    #[Vich\UploadableField(mapping: 'post', fileNameProperty: 'video')]
    private ?File $videoFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $quote = null;

    #[ORM\OneToMany(mappedBy: 'reportedPost', targetEntity: Report::class, orphanRemoval: true)]
    private Collection $reports;

    public function __construct()
    {
        $this->createdat = new DateTime();
        $this->rating=0;
        $this->comments = new ArrayCollection();
        $this->reports = new ArrayCollection();
    }
    public function getVideo(): ?string
{
    return $this->video;
}

public function setVideo(?string $video): self
{
    $this->video = $video;

    return $this;
}

public function getVideoFile(): ?File
{
    return $this->videoFile;
}

public function setVideoFile(?File $videoFile = null): self
{
    $this->videoFile = $videoFile;

    if ($videoFile) {
        $this->updatedat = new \DateTimeImmutable();
    }

    return $this;
}
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedat = new \DateTimeImmutable();
        }
    }
    public function setimage(?string $image): void
    {
        $this->image = $image;
    }

    public function getimage(): ?string
    {
        return $this->image;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }
    /**
     * @ORM\PrePersist
     */
    public function setCreatedat(\DateTimeInterface $createdat): self
    {
        $this->createdat = new DateTime();

        return $this;
    }

    public function getUsername(): ?Usser
    {
        return $this->username;
    }

    public function setUsername(?Usser $username): self
    {
        $this->username = $username;

        return $this;
    }

    
    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }


    
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setIDPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getIDPost() === $this) {
                $comment->setIDPost(null);
            }
        }

        return $this;
    }

    public function getElapsedTime(): string
    {
        $now = new DateTime();
        $interval = $this->createdat->diff($now);

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

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    

    public function getUpdatedat(): ?\DateTimeInterface
    {
        return $this->updatedat;
    }

    public function setUpdatedat(?\DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    

    public function getQuote(): ?string
    {
        return $this->quote;
    }

    public function setQuote(?string $quote): self
    {
        $this->quote = $quote;

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setReportedPost($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getReportedPost() === $this) {
                $report->setReportedPost(null);
            }
        }

        return $this;
    }
}

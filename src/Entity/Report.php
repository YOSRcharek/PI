<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $reason = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $details = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $reportedPost = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hidePost = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usser $username = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getReportedPost(): ?Post
    {
        return $this->reportedPost;
    }

    public function setReportedPost(?Post $reportedPost): self
    {
        $this->reportedPost = $reportedPost;

        return $this;
    }

    public function isHidePost(): ?bool
    {
        return $this->hidePost;
    }

    public function setHidePost(?bool $hidePost): self
    {
        $this->hidePost = $hidePost;

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
}

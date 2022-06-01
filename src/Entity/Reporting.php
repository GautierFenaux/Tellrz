<?php

namespace App\Entity;

use App\Repository\ReportingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportingRepository::class)]
class Reporting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $content;
    
    // Reporting est propriétaire de la relation
    #[ORM\ManyToOne(targetEntity: Manuscript::class, inversedBy: 'reportings')]
    private $manuscript;

    // Reporting est propriétaire de la relation
    #[ORM\ManyToOne(targetEntity: Chapter::class, inversedBy: 'reportings')]
    private $chapter;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reportings')]
    private $user;

    #[ORM\Column(type: 'text', nullable: true)]
    private $details;

    #[ORM\Column(type: 'text', nullable: true)]
    public $search;

    public function __toString()
    {
        return $this->content;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getManuscript(): ?manuscript
    {
        return $this->manuscript;
    }

    public function setManuscript(?manuscript $manuscript): self
    {
        $this->manuscript = $manuscript;

        return $this;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter): self
    {
        $this->chapter = $chapter;

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

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }





}

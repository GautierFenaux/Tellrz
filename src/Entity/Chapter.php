<?php

namespace App\Entity;

use App\Repository\ChapterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Sortable\Entity\Repository\SortableRepository;


// #[ORM\Entity(repositoryClass: SortableRepository::class)]
#[ORM\Entity(repositoryClass: ChapterRepository::class)]
class Chapter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'chapters')]
    #[ORM\JoinColumn(nullable: false)]
    private $authorId;

    //Owning side, Doctrine will only check the owning side of an association for changes.
    #[ORM\ManyToOne(targetEntity: Manuscript::class, inversedBy: 'chapters')]
    #[ORM\JoinColumn(nullable: false)]
    private $manuscriptId;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $numberOfSigns;

    #[ORM\OneToMany(mappedBy: 'chapter', targetEntity: Reporting::class)]
    private $reportings;

    
    #[ORM\Column(name: 'position', type: 'integer')]
    private int $position;


    public function __construct()
    {
        $this->reportings = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getPosition(): int
    {
        return $this->position;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
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

    public function getAuthorId(): ?User
    {
        return $this->authorId;
    }

    public function setAuthorId(?User $authorId): self
    {
        $this->authorId = $authorId;

        return $this;
    }

    public function getManuscriptId(): ?manuscript
    {
        return $this->manuscriptId;
    }

    public function setManuscriptId(?manuscript $manuscriptId): self
    {
        $this->manuscriptId = $manuscriptId;

        return $this;
    }

    public function getNumberOfSigns(): ?int
    {
        return $this->numberOfSigns;
    }

    public function setNumberOfSigns(?int $numberOfSigns): self
    {
        $this->numberOfSigns = $numberOfSigns;

        return $this;
    }

    /**
     * @return Collection|Reporting[]
     */
    public function getReportings(): Collection
    {
        return $this->reportings;
    }

    public function addReporting(Reporting $reporting): self
    {
        if (!$this->reportings->contains($reporting)) {
            $this->reportings[] = $reporting;
            $reporting->setChapter($this);
        }

        return $this;
    }

    public function removeReporting(Reporting $reporting): self
    {
        if ($this->reportings->removeElement($reporting)) {
            // set the owning side to null (unless already changed)
            if ($reporting->getChapter() === $this) {
                $reporting->setChapter(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ManuscriptRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



#[ORM\Entity(repositoryClass: ManuscriptRepository::class)]
#[Vich\Uploadable] 
class Manuscript
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $title;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'boolean')]
    private $explicitContent = false;

    #[ORM\Column(type: 'string', length: 255)]
    private $readership;


    // User est propriétaire de la relation
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'manuscript')]
    private $users;

    // Question est propriétaire de la relation
    #[ORM\ManyToMany(targetEntity: Question::class, mappedBy: 'manuscripts')]
    private $questions;

    #[ORM\OneToMany(mappedBy: 'manuscriptId', targetEntity: Chapter::class)]
    private $chapters;

    //Manuscript est propriétaire de la relation
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'manuscripts' )]
    private $author_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageName;

    /**
    * NOTE: This is not a mapped field of entity metadata, just a simple property.
    */
    #[Vich\UploadableField(mapping: 'manuscripts', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updatedAt;

    // Manuscript est propriétaire de la relation
    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'manuscripts')]
    private $genres;

    //Reporting est propriétaire de la relation
    #[ORM\OneToMany(mappedBy: 'manuscript', targetEntity: Reporting::class)]
    private $reportings;

    #[ORM\Column(type: 'string', length: 255)]
    private $author;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->reportings = new ArrayCollection();
    }

     /**
    * @return string|null
    */
    public function __toString()
    {
        
        return $this->title;
        return $this->genres;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addManuscript($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeManuscript($this);
        }

        return $this;
    }

    // /**
    //  * @return Collection|Genre[]
    //  */
    // public function getGenres(): Collection
    // {
    //     return $this->genres;
    // }

    // public function addGenre(Genre $genre): self
    // {
    //     if (!$this->genres->contains($genre)) {
    //         $this->genres[] = $genre;
    //         $genre->addManuscript($this);
    //     }

    //     return $this;
    // }

    // public function removeGenre(Genre $genre): self
    // {
    //     if ($this->genres->removeElement($genre)) {
    //         $genre->removeManuscript($this);
    //     }

    //     return $this;
    // }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->addManuscript($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            $question->removeManuscript($this);
        }

        return $this;
    }

    public function getAuthorId(): ?User
    {
        return $this->author_id;
    }

    public function setAuthorId(?User $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @return Collection|Chapter[]
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters[] = $chapter;
            $chapter->setManuscriptId($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): self
    {
        if ($this->chapters->removeElement($chapter)) {
            // set the owning side to null (unless already changed)
            if ($chapter->getManuscriptId() === $this) {
                $chapter->setManuscriptId(null);
            }
        }

        return $this;
    }

    public function getExplicitContent(): ?bool
    {
        return $this->explicitContent;
    }

    public function setExplicitContent(bool $explicitContent): self
    {
        $this->explicitContent = $explicitContent;

        return $this;
    }

    public function getReadership(): ?string
    {
        return $this->readership;
    }

    public function setReadership(string $readership): self
    {
        $this->readership = $readership;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

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
            $reporting->setManuscript($this);
        }

        return $this;
    }

    public function removeReporting(Reporting $reporting): self
    {
        if ($this->reportings->removeElement($reporting)) {
            // set the owning side to null (unless already changed)
            if ($reporting->getManuscript() === $this) {
                $reporting->setManuscript(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }


}

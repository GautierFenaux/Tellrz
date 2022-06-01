<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GenreRepository;
use Doctrine\Common\Collections\Collection;

use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $genreName;
    // MappedBy représentation de l'enfant(genres) dans le parent(manuscript) ici genres
    #[ORM\ManyToMany(targetEntity: Manuscript::class, mappedBy: 'genres', cascade:["persist"])]
    private $manuscripts;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    // #[ORM\ManyToMany(targetEntity: Manuscript::class, inversedBy: 'genres', cascade: ['persist'])]
    // private $manuscripts;

    public function __construct()
    {
        $this->manuscripts = new ArrayCollection();
    }

    /**
    * @return string|null
    */
    public function __toString()
    {
        return $this->genreName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGenreName(): ?string
    {
        return $this->genreName;
    }

    public function setGenreName(string $genreName): self
    {
        $this->genreName = $genreName;

        return $this;
    }

    // /**
    //  * @return Collection|manuscript[]
    //  */
    // public function getManuscripts(): Collection
    // {
    //     return $this->manuscripts;
    // }

    // public function addManuscript(manuscript $manuscript): self
    // {
    //     if (!$this->manuscripts->contains($manuscript)) {
    //         $this->manuscripts[] = $manuscript;
    //     }

    //     return $this;
    // }

    // public function removeManuscript(manuscript $manuscript): self
    // {
    //     $this->manuscripts->removeElement($manuscript);

    //     return $this;
    // }

    /**
     * @return Collection|Manuscript[]
     */
    public function getManuscripts(): Collection
    {
        return $this->manuscripts;
    }

    public function addManuscript(Manuscript $manuscript): self
    {
        if (!$this->manuscripts->contains($manuscript)) {
            $this->manuscripts[] = $manuscript;
            $manuscript->addGenre($this);
        }

        return $this;
    }

    public function removeManuscript(Manuscript $manuscript): self
    {
        if ($this->manuscripts->removeElement($manuscript)) {
            $manuscript->removeGenre($this);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}

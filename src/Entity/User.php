<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageName;

    #[ORM\Column(type: 'datetime_immutable', nullable:true)]
    private $updatedAt;

    #[ORM\Column(type:"boolean")]
    private $isVerified = false;
    
    // User est propriétaire de la relation avec les manuscrits
    #[ORM\ManyToMany(targetEntity: Manuscript::class, inversedBy: 'users')]
    private $manuscript;

    #[ORM\OneToMany(mappedBy: 'authorId', targetEntity: Manuscript::class, cascade: ["persist", "remove"])]
    private $authorManuscript;

    #[ORM\OneToMany(mappedBy: 'authorId', targetEntity: Chapter::class)]
    private $chapter;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Reporting::class)]
    private $reportings;
    

    /**
    * @return string|null
    */
    public function __toString()
    {
        return $this->id;
    }


    public function __construct()
    {
        $this->manuscript = new ArrayCollection();
        $this->authorManuscript = new ArrayCollection();
        $this->chapter = new ArrayCollection();
        $this->reportings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Manuscript[]
     */
    public function getManuscript(): Collection
    {
        return $this->manuscript;
    }

    public function addManuscript(Manuscript $manuscript): self
    {
        if (!$this->manuscript->contains($manuscript)) {
            $this->manuscript[] = $manuscript;
        }

        return $this;
    }

    public function removeManuscript(Manuscript $manuscript): self
    {
        $this->manuscript->removeElement($manuscript);

        return $this;
    }

    /**
     * @return Collection|Manuscript[]
     */
    public function getAuthorManuscript(): Collection
    {
        return $this->authorManuscript;
    }

    /**
     * @return Collection|Chapter[]
     */
    public function getAuthorId(): Collection
    {
        return $this->authorId;
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapter->contains($chapter)) {
            $this->chapter[] = $chapter;
            $chapter->setAuthorId($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): self
    {
        if ($this->chapter->removeElement($chapter)) {
            // set the owning side to null (unless already changed)
            if ($chapter->getAuthorId() === $this) {
                $chapter->setAuthorId(null);
            }
        }

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
            $reporting->setUser($this);
        }

        return $this;
    }

    public function removeReporting(Reporting $reporting): self
    {
        if ($this->reportings->removeElement($reporting)) {
            // set the owning side to null (unless already changed)
            if ($reporting->getUser() === $this) {
                $reporting->setUser(null);
            }
        }

        return $this;
    }
}

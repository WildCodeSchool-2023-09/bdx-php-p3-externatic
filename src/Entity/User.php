<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: "L'email existe déjà")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;


    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkedin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Company $company = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Candidate $candidate = null;

    #[ORM\ManyToMany(targetEntity: Job::class, mappedBy: 'likingUsers')]
    private Collection $likelist;

    #[ORM\ManyToMany(targetEntity: Candidate::class, mappedBy: 'likingCompanies')]
    private Collection $companyLikelist;

    public function __construct()
    {
        $this->likelist = new ArrayCollection();
        $this->companyLikelist = new ArrayCollection();
    }

    public function getCompanyLikelist(): Collection
    {
        return $this->companyLikelist;
    }

    public function addToCompanyLikelist(Candidate $candidate): self
    {
        if (!$this->companyLikelist->contains($candidate)) {
            $this->companyLikelist->add($candidate);
        }

        return $this;
    }

    public function removeFromCompanyLikelist(Candidate $candidate): self
    {
        $this->companyLikelist->removeElement($candidate);

        return $this;
    }
    public function getLikelist(): Collection
    {
        return $this->likelist;
    }

    public function addToLikelist(Job $job): self
    {
        if (!$this->likelist->contains($job)) {
            $this->likelist->add($job);
        }

        return $this;
    }

    public function removeFromLikelist(Job $job): self
    {
        $this->likelist->removeElement($job);

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
        return (string)$this->email;
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

    public function setRoles(array $roles): static // tested changing from :static to :self??
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): static
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): static
    {
        // set the owning side of the relation if necessary
        if ($company->getUser() !== $this) {
            $company->setUser($this);
        }

        $this->company = $company;

        return $this;
    }

    public function getCandidate(): ?Candidate
    {
        return $this->candidate;
    }

    public function setCandidate(Candidate $candidate): static
    {
        // set the owning side of the relation if necessary
        if ($candidate->getUser() !== $this) {
            $candidate->setUser($this);
        }

        $this->candidate = $candidate;

        return $this;
    }
}

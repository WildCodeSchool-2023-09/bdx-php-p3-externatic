<?php

namespace App\Entity;

use App\Repository\CandidateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidateRepository::class)]
class Candidate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $github = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fonction = null;

    #[ORM\OneToOne(inversedBy: 'candidate', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'CompanyLikelist')]
    #[ORM\JoinTable(name: 'CompanyLikeList')]
    private Collection $likingCompanies;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profileCV = null;

    public function __construct()
    {
        $this->likingCompanies = new ArrayCollection();
    }

    public function isLikedByCompany(User $user): bool
    {
        return $this->likingCompanies->contains($user);
    }

    public function getLikingCompanies(): Collection
    {
        return $this->likingCompanies;
    }

    public function addLikingCompanies(User $user): self
    {
        if (!$this->likingCompanies->contains($user)) {
            $this->likingCompanies[] = $user;
        }

        return $this;
    }

    public function removeLikingCompanies(User $user): self
    {
        $this->likingCompanies->removeElement($user);

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): static
    {
        $this->github = $github;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(?string $fonction): static
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->getUser()?->getLocation();
    }

    public function setLocation(?string $location): static
    {
        $this->getUser()?->setLocation($location);

        return $this;
    }

    public function getProfileCV(): ?string
    {
        return $this->profileCV;
    }

    public function setProfileCV(?string $profileCV): static
    {
        $this->profileCV = $profileCV;

        return $this;
    }
}

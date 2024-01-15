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

    #[ORM\ManyToMany(targetEntity: Company::class, inversedBy: 'favoriteCandidates')]
    private Collection $favoriteCompanies;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profileCV = null;

    public function __construct()
    {
        $this->favoriteCompanies = new ArrayCollection();
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

    /**
     * @return Collection<int, Company>
     */
    public function getFavoriteCompanies(): Collection
    {
        return $this->favoriteCompanies;
    }

    public function addFavoriteCompany(Company $favoriteCompany): static
    {
        if (!$this->favoriteCompanies->contains($favoriteCompany)) {
            $this->favoriteCompanies->add($favoriteCompany);
        }

        return $this;
    }

    public function removeFavoriteCompany(Company $favoriteCompany): static
    {
        $this->favoriteCompanies->removeElement($favoriteCompany);

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

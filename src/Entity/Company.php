<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress = null;

    #[ORM\OneToOne(inversedBy: 'company', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Job::class, orphanRemoval: true)]
    private Collection $jobs;

    #[ORM\ManyToMany(targetEntity: Candidate::class, mappedBy: 'favoriteCompanies')]
    private Collection $favoriteCandidates;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->favoriteCandidates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): static
    {
        $this->adress = $adress;

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
     * @return Collection<int, Job>
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): static
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
            $job->setCompany($this);
        }

        return $this;
    }

    public function removeJob(Job $job): static
    {
        if ($this->jobs->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getCompany() === $this) {
                $job->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Candidate>
     */
    public function getFavoriteCandidates(): Collection
    {
        return $this->favoriteCandidates;
    }

    public function addFavoriteCandidate(Candidate $favoriteCandidate): static
    {
        if (!$this->favoriteCandidates->contains($favoriteCandidate)) {
            $this->favoriteCandidates->add($favoriteCandidate);
            $favoriteCandidate->addFavoriteCompany($this);
        }

        return $this;
    }

    public function removeFavoriteCandidate(Candidate $favoriteCandidate): static
    {
        if ($this->favoriteCandidates->removeElement($favoriteCandidate)) {
            $favoriteCandidate->removeFavoriteCompany($this);
        }

        return $this;
    }
}

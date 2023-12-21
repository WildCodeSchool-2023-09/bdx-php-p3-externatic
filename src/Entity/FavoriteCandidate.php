<?php

namespace App\Entity;

use App\Repository\FavoriteCandidateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteCandidateRepository::class)]
class FavoriteCandidate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Candidate::class, inversedBy: 'favoriteCandidates')]
    private Collection $candidate;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'favoriteCandidates')]
    private Collection $company;

    public function __construct()
    {
        $this->candidate = new ArrayCollection();
        $this->company = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidate(): Collection
    {
        return $this->candidate;
    }

    public function addCandidate(Candidate $candidate): static
    {
        if (!$this->candidate->contains($candidate)) {
            $this->candidate->add($candidate);
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): static
    {
        $this->candidate->removeElement($candidate);

        return $this;
    }

    public function getCompany(): Collection
    {
        return $this->company;
    }

    public function addCompany(Company $company): static
    {
        if (!$this->company->contains($company)) {
            $this->company->add($company);
        }

        return $this;
    }

    public function removeCompany(Company $company): static
    {
        $this->company->removeElement($company);

        return $this;
    }
}

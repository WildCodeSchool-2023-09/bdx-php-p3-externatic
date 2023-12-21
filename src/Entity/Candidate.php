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

    #[ORM\OneToOne(inversedBy: 'candidate', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: FavoriteCandidate::class, mappedBy: 'candidate')]
    private Collection $favoriteCandidates;

    public function __construct()
    {
        $this->favoriteCandidates = new ArrayCollection();
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
     * @return Collection<int, FavoriteCandidate>
     */
    public function getFavoriteCandidates(): Collection
    {
        return $this->favoriteCandidates;
    }

    public function addFavoriteCandidate(FavoriteCandidate $favoriteCandidate): static
    {
        if (!$this->favoriteCandidates->contains($favoriteCandidate)) {
            $this->favoriteCandidates->add($favoriteCandidate);
            $favoriteCandidate->addCandidate($this);
        }

        return $this;
    }

    public function removeFavoriteCandidate(FavoriteCandidate $favoriteCandidate): static
    {
        if ($this->favoriteCandidates->removeElement($favoriteCandidate)) {
            $favoriteCandidate->removeCandidate($this);
        }

        return $this;
    }
}

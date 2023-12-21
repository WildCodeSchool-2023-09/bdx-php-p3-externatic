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

    #[ORM\OneToMany(mappedBy: 'candidate', targetEntity: CVs::class)]
    private Collection $cvs;

    public function __construct()
    {
        $this->cvs = new ArrayCollection();
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
     * @return Collection<int, CVs>
     */
    public function getCVs(): Collection
    {
        return $this->cvs;
    }

    public function addCV(CVs $cvs): static
    {
        if (!$this->cvs->contains($cvs)) {
            $this->cvs->add($cvs);
            $cvs->setCandidate($this);
        }

        return $this;
    }

    public function removeCV(CVs $cvs): static
    {
        if ($this->cvs->removeElement($cvs)) {
            // set the owning side to null (unless already changed)
            if ($cvs->getCandidate() === $this) {
                $cvs->setCandidate(null);
            }
        }

        return $this;
    }
}

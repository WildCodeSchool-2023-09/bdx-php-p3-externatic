<?php

namespace App\Twig\Components;

use App\Entity\Candidate;
use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class CompanyLikelist
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public Candidate $candidate;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[LiveAction]
    public function toggle(): void
    {
        /** @var Company $company */
        $company = $this->security->getUser();

        // Check if the company has already added this candidate to their likelist
        if ($company->getFavoriteCandidates()->contains($this->candidate)) {
            // If yes, remove the candidate from the company's likelist
            // and remove the company from the candidate's list of favorite companies
            $company->removeFavoriteCandidate($this->candidate);
            $this->candidate->removeFavoriteCompany($company);
        } else {
            // If no, add the candidate to the company's likelist
            // and add the company to the candidate's list of favorite companies
            $company->addFavoriteCandidate($this->candidate);
            $this->candidate->addFavoriteCompany($company);
        }

        // Save the changes to the database
        $this->entityManager->persist($company);
        $this->entityManager->persist($this->candidate);
        $this->entityManager->flush();
    }
}

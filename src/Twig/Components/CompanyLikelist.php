<?php

namespace App\Twig\Components;

use App\Entity\Candidate;
use App\Entity\User;
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
        /** @var User $user */
        $user = $this->security->getUser();

        // Vérifier si l'utilisateur a déjà ajouté ce candidat à sa liste de favoris
        if ($user->getCompanyLikelist()->contains($this->candidate)) {
            // Si oui, le retirer de la liste de favoris de l'utilisateur
            // et de la liste des entreprises aimant ce candidat
            $user->removeFromCompanyLikelist($this->candidate);
            $this->candidate->removeLikingCompanies($user);
        } else {
            // Si non, ajouter ce candidat à la liste de favoris de l'utilisateur
            // et à la liste des entreprises aimant ce candidat
            $user->addToCompanyLikelist($this->candidate);
            $this->candidate->addLikingCompanies($user);
        }

        // Enregistrer les modifications dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->persist($this->candidate);
        $this->entityManager->flush();
    }
}

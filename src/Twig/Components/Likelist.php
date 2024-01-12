<?php

namespace App\Twig\Components;

use App\Entity\User;
use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class Likelist
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public Job $job;
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

        // Vérifier si l'utilisateur a déjà ajouté cet emploi à sa liste de favoris
        if ($user->getLikelist()->contains($this->job)) {
            // Si oui, le retirer de la liste de favoris de l'utilisateur
            // et de la liste des utilisateurs aimant cet emploi
            $user->removeFromLikelist($this->job);
            $this->job->removeLikingUser($user);
        } else {
            // Si non, ajouter cet emploi à la liste de favoris de l'utilisateur
            // et à la liste des utilisateurs aimant cet emploi
            $user->addToLikelist($this->job);
            $this->job->addLikingUser($user);
        }

        // Enregistrer les modifications dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->persist($this->job);
        $this->entityManager->flush();
    }
}

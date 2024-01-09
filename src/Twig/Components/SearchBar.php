<?php

namespace App\Twig\Components;

use App\Repository\JobRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
final class SearchBar extends AbstractController
{
    use DefaultActionTrait;

    //private ?RequestStack $requestStack;

    private JobRepository $jobRepository;
    private UserRepository $userRepository;

    #[LiveProp(writable: true)]
    public string $queryTitle = '';

    #[LiveProp(writable: true)]
    public string $queryLocation = '';

    public function __construct(
        //?RequestStack $requestStack,
        JobRepository $jobRepository,
        UserRepository $userRepository
    ) {
        //$this->requestStack = $requestStack;
        $this->jobRepository = $jobRepository;
        $this->userRepository = $userRepository;
    }

    public function search(): array
    {
        //$requestStack = $this->requestStack;

        $title = $this->queryTitle;
        $location = $this->queryLocation;

        $results = [];

        $user = $this->getUser();
        $roles = $user ? $user->getRoles() : [];

        // Logique de recherche en fonction des rôles des utilisateurs
        if (in_array('ROLE_CANDIDAT', $roles) || !$user) {
            // Utilisateur avec le rôle ROLE_CANDIDAT ou utilisateur non connecté
            $results = $this->jobRepository->searchByTitleAndLocation($title, $location);
        } elseif (in_array('ROLE_COMPANY', $roles)) {
            // Utilisateur avec le rôle ROLE_COMPANY
            $results = $this->userRepository->searchByFunctionAndLocation($title, $location);
        }

        return ['results' => $results];
    }
}

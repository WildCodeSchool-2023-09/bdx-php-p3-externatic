<?php

namespace App\Controller;

use App\Repository\CandidateRepository;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    #[Route('/favorite/jobs', name: 'app_favorite_jobs')]
    public function favoriteJobs(JobRepository $jobRepository): Response
    {
        // Récupère l'utilisateur actuel
        $user = $this->getUser();

        // Récupère la liste des emplois favoris pour l'utilisateur
        $favoriteJobs = $jobRepository->findByLikedUser($user);

        return $this->render('favorite/favorite_jobs.html.twig', [
            'favoriteJobs' => $favoriteJobs,
        ]);
    }

    #[Route('/favorite/candidates', name: 'app_favorite_candidates')]
    public function favoriteCandidates(CandidateRepository $candidateRepository): Response
    {
        // Récupère l'utilisateur actuel
        $user = $this->getUser();

        // Récupère la liste des candidats favoris pour l'utilisateur avec le rôle COMPANY
        $favoriteCandidates = $candidateRepository->findByLikedCompany($user);

        return $this->render('favorite/favorite_candidates.html.twig', [
            'favoriteCandidates' => $favoriteCandidates,
        ]);
    }
}

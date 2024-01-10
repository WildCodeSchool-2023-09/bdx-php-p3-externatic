<?php

namespace App\Controller;

use App\Form\CandidateSearchFormType;
use App\Form\JobSearchFormType;
use App\Repository\CandidateRepository;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        JobRepository $jobRepository,
        CandidateRepository $candidateRepository
    ): Response {
        // Formulaire pour trouver un job
        $form = $this->createForm(JobSearchFormType::class);
        $form->handleRequest($request);
        $jobs = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form->get('title')->getData();
            $location = $form->get('city')->getData();
            // Vérifie si $title et $location sont null et leur donne une valeur par défaut
            $title = $title ?? '';
            $location = $location ?? '';
            // Effectue recherche en fonction du titre du job
            $jobs = $jobRepository->searchByTitleAndLocation($title, $location);
        } else {
            // Si le formulaire n'est pas soumis, affiche les 8 dernières annonces
            $jobs = $jobRepository->findBy([], ['id' => 'DESC'], 8);
        }

        // Formulaire pour trouver un candidat
        $candidateSearchForm = $this->createForm(CandidateSearchFormType::class);
        $candidateSearchForm->handleRequest($request);
        $candidates = [];

        if ($candidateSearchForm->isSubmitted() && $candidateSearchForm->isValid()) {
            $fonction = $candidateSearchForm->get('fonction')->getData();
            $location = $candidateSearchForm->get('location')->getData();
            // Effectue la recherche en fonction de la fonction et de la location
            $candidates = $candidateRepository->searchByFunctionAndLocation($fonction, $location);
        } else {
            // Si le formulaire n'est pas soumis, affiche les 8 derniers candidats inscrits
            $candidates = $candidateRepository->findBy([], ['id' => 'DESC'], 8);
        }

        return $this->render('home/index.html.twig', [
            'searchForm' => $form->createView(),
            'candidateSearchForm' => $candidateSearchForm->createView(),
            'jobs' => $jobs,
            'candidates' => $candidates,
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }

    #[Route('/aboutCompany', name: 'app_aboutCompany')]
    public function aboutCompany(): Response
    {
        return $this->render('home/about-compagny.html.twig');
    }
}

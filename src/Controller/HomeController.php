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
        // Crée un formulaire de recherche d'emploi en utilisant JobSearchFormType
        $form = $this->createForm(JobSearchFormType::class);

        // Gère la soumission et la validation du formulaire
        $form->handleRequest($request);

        // Initialise un tableau vide pour stocker les résultats des offres d'emploi
        $jobs = [];

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère le titre et l'emplacement du formulaire
            $title = $form->get('title')->getData();
            $location = $form->get('city')->getData();

            // Assure que $title et $location ont des valeurs par défaut s'ils sont nuls
            $title = $title ?? '';
            $location = $location ?? '';

            // Effectue une recherche en fonction du titre et de l'emplacement de l'emploi
            $jobs = $jobRepository->searchByTitleAndLocation($title, $location);
        } else {
            // Si le formulaire n'est pas soumis, affiche les 8 dernières annonces
            $jobs = $jobRepository->findBy([], ['id' => 'DESC'], 8);
        }

        // Formulaire pour trouver un candidat
        $candidateSearchForm = $this->createForm(CandidateSearchFormType::class);

        // Gère la soumission et la validation du formulaire de recherche de candidat
        $candidateSearchForm->handleRequest($request);

        // Initialise un tableau vide pour stocker les résultats des candidats
        $candidates = [];

        // Vérifie si le formulaire de recherche de candidat est soumis et valide
        if ($candidateSearchForm->isSubmitted() && $candidateSearchForm->isValid()) {
            // Récupère la fonction et l'emplacement du formulaire
            $fonction = $candidateSearchForm->get('fonction')->getData();
            $location = $candidateSearchForm->get('location')->getData();

            // Effectue une recherche en fonction de la fonction et de l'emplacement du candidat
            $candidates = $candidateRepository->searchByFunctionAndLocation($fonction, $location);
        } else {
            // Si le formulaire n'est pas soumis, affiche les 8 derniers candidats inscrits
            $candidates = $candidateRepository->findBy([], ['id' => 'DESC'], 8);
        }

        // Passe au template les forms et résultats
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

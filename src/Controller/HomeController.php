<?php

namespace App\Controller;

use App\Form\JobSearchFormType;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, JobRepository $jobRepository): Response
    {
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
            /*// Si le formulaire n'est pas soumis, affiche tous les jobs par défaut
            $jobs = $jobRepository->findAll();*/
            // Si le formulaire n'est pas soumis, affiche les 8 dernières annonces
            $jobs = $jobRepository->findBy([], ['id' => 'DESC'], 8);
        }

        return $this->render('home/index.html.twig', [
            'searchForm' => $form->createView(),
            'jobs' => $jobs,
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }

    #[Route('/aboutCompagny', name: 'app_aboutCompagny')]
    public function aboutCompagny(): Response
    {
        return $this->render('home/about-compagny.html.twig');
    }
}

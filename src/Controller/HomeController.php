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

            // Effectue ici l'action de recherche en fonction du titre du job
            $jobs = $jobRepository->searchByTitleAndLocation($title, $location);
        } else {
            // Si le formulaire n'est pas soumis, affichez tous les jobs par défaut
            $jobs = $jobRepository->findAll();
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
}

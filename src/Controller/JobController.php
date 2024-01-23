<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use App\Repository\ApplicationRepository;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/job')]
class JobController extends AbstractController
{
    /*#[Route('/', name: 'app_job_index', methods: ['GET'])]
    public function index(JobRepository $jobRepository): Response
    {
        return $this->render('job/index.html.twig', [
            'jobs' => $jobRepository->findAll(),
        ]);
    }*/

    #[Route('/', name: 'app_job_index', methods: ['GET'])]
    public function index(JobRepository $jobRepository): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_COMPANY') && $user->getCompany() !== null) {
            // Si user a rôle ROLE_COMPANY affiche les jobs de cette entreprise
            $jobs = $jobRepository->findBy(['company' => $user->getCompany()]);

            return $this->render('job/index.html.twig', [
                'jobs' => $jobs,
            ]);
        } elseif ($this->isGranted('ROLE_ADMIN')) {
            // Si l'utilisateur a le rôle ROLE_ADMIN, afficher tous les jobs
            $jobs = $jobRepository->findAll();

            return $this->render('job/index.html.twig', [
                'jobs' => $jobs,
            ]);
        } else {
            // Si l'utilisateur n'a ni le rôle ROLE_COMPANY ni le rôle ROLE_ADMIN
            return $this->redirectToRoute('app_home');
        }
    }

    #[Route('/new', name: 'app_job_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($job);
            $entityManager->flush();

            return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job/new.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_job_show', methods: ['GET'])]
    public function show(Job $job): Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_job_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Job $job, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JobType::class, $job);

        $form->get('company')->createView()->vars['disabled'] = true;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job/edit.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_job_delete', methods: ['POST'])]
    public function delete(Request $request, Job $job, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $job->getId(), $request->request->get('_token'))) {
            $entityManager->remove($job);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/applicants', name: 'app_job_applicants')]
    public function applicants(): Response
    {

        return $this->render('job/applicants.html.twig');
    }
}

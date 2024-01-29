<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Candidate;
use App\Entity\Job;
use App\Form\JobType;
use App\Repository\ApplicationRepository;
use App\Repository\CandidateRepository;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use App\Form\StatusFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/job')]
class JobController extends AbstractController
{
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
    public function edit(
        Request $request,
        Job $job,
        EntityManagerInterface $entityManager,
        JobRepository $jobRepository
    ): Response {
        // for security check the company is linked to this job to allow permission to edit or delete
        $user = $this->getUser();
        $company = $user->getCompany();
        $companyJob = $jobRepository->findOneBy(['company' => $company, 'id' => $job->getId()]);

        if ($companyJob !== null) {
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
        } else {
            throw new AccessDeniedException("vous n'êtes pas autorisé à modifier cet emploi");
        }
    }

    #[IsGranted('ROLE_COMPANY')]
    #[Route('/{id}', name: 'app_job_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Job $job,
        EntityManagerInterface $entityManager,
        JobRepository $jobRepository
    ): Response {
        $user = $this->getUser();
        $company = $user->getCompany();
        $companyJob = $jobRepository->findOneBy(['company' => $company, 'id' => $job->getId()]);

        if ($companyJob !== null) {
            if ($this->isCsrfTokenValid('delete' . $job->getId(), $request->request->get('_token'))) {
                $entityManager->remove($job);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
        } else {
            throw new AccessDeniedException("vous n'êtes pas autorisé à modifier cet emploi");
        }
    }

    #[Route('/{id}/applications', name: 'app_job_applications')]
    public function applications(
        Job $job,
        ApplicationRepository $applicationRepo,
        JobRepository $jobRepository
    ): Response {
        $user = $this->getUser();
        $company = $user->getCompany();
        $companyJob = $jobRepository->findOneBy(['company' => $company, 'id' => $job->getId()]);

        if ($companyJob !== null) {
            $applications = $applicationRepo->findBy(['job' => $job]);

            return $this->render('job/applications.html.twig', [
            'applications' => $applications,
            'job' => $job,

            ]);
        } else {
            throw new AccessDeniedException("accès refusé");
        }
    }

    #[Route('/job/{id}/applications/{application_id}', name: 'app_job_decision')]
    public function editStatus(
        Job $job,
        #[MapEntity(expr: 'repository.find(application_id)')]
        Application $application,
        Request $request,
        EntityManagerInterface $entityManager,
        JobRepository $jobRepository
    ): Response {
        $user = $this->getUser();
        $company = $user->getCompany();
        $companyJob = $jobRepository->findOneBy(['company' => $company, 'id' => $job->getId()]);

        if ($companyJob !== null) {
            $statusForm = $this->createForm(StatusFormType::class, $application);
            $statusForm->handleRequest($request);

            if ($statusForm->isSubmitted()) {
                $entityManager->persist($application);
                $entityManager->flush();

                $this->addFlash('decision', 'le statut a été mis à jour ');
                return $this->redirectToRoute('app_job_applications', ['id' => $job->getId()]);
            }

            return $this->render('job/application_decision.html.twig', [
                'statusForm' => $statusForm->createView(),
                'application' => $application,
                'job' => $job,
            ]);
        } else {
            throw new AccessDeniedException("accès refusé");
        }
    }
}

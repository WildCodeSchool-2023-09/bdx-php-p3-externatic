<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Candidate;
use App\Entity\CVs;
use App\Entity\Job;
use App\Form\ApplyFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApplyController extends AbstractController
{
    #[Route('/apply/{id}', name: 'app_apply_job', methods: ['GET', 'POST'])]
    public function applyJob(
        Security $security,
        Request $request,
        Job $job,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        if (!$user || !$security->isGranted('ROLE_CANDIDAT')) {
            throw $this->createNotFoundException('Vous devez être connecté pour postuler à un emploi.');
        }

        /** @var Candidate|null $candidate */
        $candidate = $user->getCandidate();

        if (!$candidate) {
            throw $this->createNotFoundException('Vous devez être un candidat pour postuler à un emploi.');
        }

        // Créez une nouvelle instance d'Application
        $application = new Application();
        $application->setJob($job);
        $application->setStatus('Pending');

        // Créez le formulaire
        $form = $this->createForm(ApplyFormType::class, $application);

        // Gérez la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $application = $form->getData();
            $application->setJob($job);

            $application->setStatus('Pending');

            $cvCandidate = new CVs();
            $cvCandidate->setName('CV_' . $candidate->getId());
            $cvCandidate->setCandidate($candidate);
            $cvCandidate->setApplication($application);

            $entityManager->persist($application);
            $entityManager->persist($cvCandidate);
            $entityManager->flush();

            $this->addFlash('success', 'Votre candidature a été soumise avec succès.');

            return $this->redirectToRoute('app_job_show', ['id' => $job->getId()]);
        }

        // Rendez la vue avec le formulaire
        return $this->render('apply/apply_job.html.twig', [
            'form' => $form->createView(),
            'job' => $job,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Candidate;
use App\Entity\CVs;
use App\Entity\Job;
use App\Form\ApplyFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

class ApplyController extends AbstractController
{
    #[Route('/apply/{id}', name: 'app_apply_job', methods: ['GET', 'POST'])]
    public function applyJob(
        Request $request,
        Job $job,
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader
    ): Response {
        $user = $this->getUser();

        if (!$user) {
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

            $cvChoice = $form->get('cvChoice')->getData();

            if ($cvChoice === 'new') {
                $newCVFile = $form->get('newCV')->getData();
                $newCVFileName = $fileUploader->upload($newCVFile);

                // Enregistre le nouveau CV dans la base de données
                $newCV = new CVs();
                $newCV->setName($newCVFileName);
                $newCV->setCandidate($candidate);
                $newCV->setApplication($application);

                $entityManager->persist($newCV);
            } elseif ($cvChoice === 'default') {
                $defaultCVPath = $candidate->getProfileCV();
                $defaultCV = new CVs();
                $defaultCV->setName($defaultCVPath);
                $defaultCV->setCandidate($candidate);
                $defaultCV->setApplication($application);

                $entityManager->persist($defaultCV);
            }

            $entityManager->persist($application);
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

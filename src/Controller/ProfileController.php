<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Company;
use App\Entity\CVs;
use App\Entity\User;
use App\Form\CandidateProfileType;
use App\Form\CompanyProfileType;
use App\Form\ProfileType;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }


    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader,
    ): Response {
        // Récupère l'utilisateur actuellement connecté
        $user = $this->getUser();
        // Crée des instances de Company et Candidate associées à l'utilisateur
        $company = new Company();
        $candidate = new Candidate();
        $candidate->setUser($user);
        $company->setUser($user);
        // Crée des formulaires pour le profil, la société et le candidat
        $profileForm = $this->createForm(ProfileType::class, $user);
        $companyForm = $this->createForm(CompanyProfileType::class, $company);
        $candidateForm = $this->createForm(CandidateProfileType::class, $candidate);
        // Gère les soumissions de formulaires
        $profileForm->handleRequest($request);
        $companyForm->handleRequest($request);
        $candidateForm->handleRequest($request);

        // Si le formulaire de profil est soumis et valide
        if ($profileForm->isSubmitted() /* && $profileForm->isValid()*/) {
            /** @var UploadedFile $imageFile */
            // Récupère le fichier image du formulaire et le télécharge
            $imageFile = $profileForm->get('image')->getData();
            if ($imageFile) {
                $imageFile = $fileUploader->upload($imageFile);
                $user->setImage($imageFile);
            }
            // Persiste les changements dans la base de données
            $entityManager->persist($user);
            // $entityManager->flush();

            // Si le formulaire de la compagnie est soumis et valide
            if ($companyForm->isSubmitted() /* && $companyForm->isValid()*/) {
                // Associe la société à l'utilisateur et persiste les changements
                $company->setUser($user);
                $entityManager->persist($company);
            }

            // Si le formulaire du candidat est soumis et valide
            if ($candidateForm->isSubmitted() && $candidateForm->isValid()) {
                /** @var UploadedFile $cvFile */
                // Récupère le fichier CV du formulaire et le télécharge
                $cvFile = $candidateForm->get('profileCV')->getData();
                if ($cvFile) {
                    $cvFile = $fileUploader->upload($cvFile);
                    $candidate->setProfileCV($cvFile);
                }
                // Associe le candidat à l'utilisateur et persiste les changements
                $candidate->setUser($user);
                $entityManager->persist($candidate);
            }
            // Persiste tous les changements dans la base de données
            $entityManager->flush();

            // Redirige vers la page de profil
            return $this->redirectToRoute('app_profile', [
                'id' => $user->getId(),
            ]);
        }

        // Rend la vue d'édition du profil avec les formulaires
        return $this->render(
            'profile/edit.html.twig',
            [
                'profileForm' => $profileForm->createView(), 'companyForm' => $companyForm->createView(),
                'candidateForm' => $candidateForm->createView(),
            ]
        );
    }

    #[Route('/profile/delete', name: 'app_profile_delete', methods: ['POST'])]
    public function delete(Request $request, UserRepository $userRepository): Response
    {
        // Récupère l'utilisateur actuellement connecté
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            // Supprime l'utilisateur de la base de données
            $userRepository->remove($user, true);
        }
        // Invalide la session et déconnecte l'utilisateur
        $request->getSession()->invalidate();
        $this->container->get('security.token_storage')->setToken(null);

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/profile/candidate/{id}', name: 'app_profile_candidate')]
    public function viewCandidateProfile(Candidate $candidate): Response
    {
        return $this->render('profile/candidate_profile.html.twig', [
            'candidate' => $candidate,
        ]);
    }
}

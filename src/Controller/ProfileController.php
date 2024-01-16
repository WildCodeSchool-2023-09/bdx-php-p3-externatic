<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Company;
use App\Entity\CVs;
use App\Entity\User;
use App\Form\CandidateCVType;
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
        $user = $this->getUser();
        $company = new Company();
        $candidate = new Candidate();
        $candidate->setUser($user);
        $company->setUser($user);
        $profileForm = $this->createForm(ProfileType::class, $user);
        $companyForm = $this->createForm(CompanyProfileType::class, $company);
        $candidateForm = $this->createForm(CandidateProfileType::class, $candidate);
        $profileForm->handleRequest($request);
        $companyForm->handleRequest($request);
        $candidateForm->handleRequest($request);

        if ($profileForm->isSubmitted() /* && $profileForm->isValid()*/) {
            /** @var UploadedFile $imageFile */
            $imageFile = $profileForm->get('image')->getData();
            if ($imageFile) {
                $imageFile = $fileUploader->upload($imageFile);
                $user->setImage($imageFile);
            }
            $entityManager->persist($user);
            // $entityManager->flush();

            /*if ($companyForm->isSubmitted() && $companyForm->isValid()) {
                $company->setUser($user);
                $entityManager->persist($company);
            }*/

            if ($companyForm->isSubmitted()) {
                $company->setUser($user);
                $entityManager->persist($company);
            }

            if ($candidateForm->isSubmitted() && $candidateForm->isValid()) {
                /** @var UploadedFile $cvFile */
                $cvFile = $candidateForm->get('profileCV')->getData();
                if ($cvFile) {
                    $cvFile = $fileUploader->upload($cvFile);
                    $candidate->setProfileCV($cvFile);
                }
                $candidate->setUser($user);
                $entityManager->persist($candidate);
            }
            $entityManager->flush();


            return $this->redirectToRoute('app_profile', [
                'id' => $user->getId(),
            ]);
        }


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
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }
        $request->getSession()->invalidate();
        $this->container->get('security.token_storage')->setToken(null);

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

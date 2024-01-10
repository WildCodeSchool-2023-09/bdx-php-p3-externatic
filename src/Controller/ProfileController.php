<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Company;
use App\Entity\User;
use App\Form\CandidateProfileType;
use App\Form\CompanyProfileType;
use App\Form\ProfileType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
        CompanyRepository $companyRepository
    ): Response {
        $user = $this->getUser();
        $company = new Company();
        $candidate = new Candidate();
        $candidate->setUser($user);
        $company->setUser($user); // need to change this to ensure data is saved in correct place!
        $profileForm = $this->createForm(ProfileType::class, $user);
        $companyForm = $this->createForm(CompanyProfileType::class, $company);
        $candidateForm = $this->createForm(CandidateProfileType::class, $candidate);
        $profileForm->handleRequest($request);
        $companyForm->handleRequest($request);
        $candidateForm->handleRequest($request);
        if ($profileForm->isSubmitted() && $profileForm->isValid()) { // add an and or statment here?
            /** @var UploadedFile $imageFile */
            $imageFile = $profileForm->get('image')->getData();
            if ($imageFile) {
                $imageFile = $fileUploader->upload($imageFile);
                $user->setImage($imageFile);
            }


            $entityManager->persist($user);
            $entityManager->flush();

            if ($companyForm->isSubmitted() && $companyForm->isValid()) {
                $company->setUser($user);
                // user_id cannot be null (user id = this get user?)
            }

                $entityManager->persist($company);
                $entityManager->flush();

            if ($candidateForm->isSubmitted() && $candidateForm->isValid()) {
                $candidate->setUser($user);
                // user_id cannot be null (user id = this get user?)
            }

            $entityManager->persist($candidate);
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



        // if profile and company forms submitted and valid, persist both, else profile and candidat, persist those..
    }
}

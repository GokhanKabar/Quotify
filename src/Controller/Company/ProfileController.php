<?php

namespace App\Controller\Company;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/edit', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company = $user->getCompany();

            if (!$company) {
                $this->addFlash('error', 'Aucune entreprise associée à votre profil. Veuillez contacter l\'administration.');
                return $this->redirectToRoute('profile_edit');
            }

            $companyName = $form->get('companyName')->getData();
            $companyAddress = $form->get('companyAddress')->getData();
            $companyEmail = $form->get('companyEmail')->getData();
            $companySiretNumber = $form->get('companySiretNumber')->getData();

            $company->setCompanyName($companyName);
            $company->setAddress($companyAddress);
            $company->setEmail($companyEmail);
            $company->setSiretNumber($companySiretNumber);

            $entityManager->flush();

            $this->addFlash('success', 'Votre profil et les informations de l\'entreprise ont été mis à jour avec succès.');
            return $this->redirectToRoute('company_profile_edit');
        } else {
            if ($form->isSubmitted()) {
                $this->addFlash('error', 'Des erreurs ont été détectées dans le formulaire.');
            }
        }

        return $this->render('company/profile/edit.html.twig', ['form' => $form->createView(),]);
    }
}

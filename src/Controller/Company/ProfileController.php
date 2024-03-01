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

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->flush();

                // Ajout d'un message flash de succès
                $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');

                return $this->redirectToRoute('company_profile_edit');
            } else {
                // Optionnel: Ajout d'un message flash d'échec
                $this->addFlash('error', 'Des erreurs ont été détectées dans le formulaire.');
            }
        }

        return $this->render('company/profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

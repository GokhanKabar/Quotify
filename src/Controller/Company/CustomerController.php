<?php

namespace App\Controller\Company;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use App\Repository\CompanyRepository;

#[Route('/customer')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'customer_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Security $security): Response
    {
        $company = $security->getUser()->getCompany();

        $users = $userRepository->findBy(['company' => $company]);

        return $this->render('company/customer/index.html.twig', ['users' => $users, 'company' => $company]);
    }

    #[Route('/new', name: 'customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = new User();
        $user->setCompany($security->getUser()->getCompany());

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger vers la liste des utilisateurs après la création
            return $this->redirectToRoute('company_customer_index');
        }

        return $this->render('company/customer/new.html.twig', ['user' => $user, 'form' => $form->createView(),]);
    }

    #[Route('/{id}', name: 'customer_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('company/customer/show.html.twig', ['user' => $user,]);
    }

    #[Route('/{id}/edit', name: 'customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('company_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/customer/edit.html.twig', ['user' => $user, 'form' => $form->createView(),]);
    }

    #[Route('/{id}', name: 'customer_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $token = new CsrfToken('delete' . $user->getId(), $request->request->get('_token'));
        if ($csrfTokenManager->isTokenValid($token)) {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Jeton de sécurité invalide, impossible de supprimer l\'utilisateur.');
        }

        return $this->redirectToRoute('company_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}

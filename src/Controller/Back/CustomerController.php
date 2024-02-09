<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\CustomerType;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/customer')]
class CustomerController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'customer_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $company = $this->getUser()->getCompany();

        $users = $userRepository->findBy(['company' => $company]);

        return $this->render('back/customer/index.html.twig', ['users' => $users, 'company' => $company,]);
    }

    #[Route('/invoices', name: 'customer_invoices')]
    public function invoices_list(CompanyRepository $companyRepository): Response
    {
        $user = $this->security->getUser();
        $invoices = $companyRepository->getInvoices($user->getCompany()->getId());

        return $this->render('back/customer/invoices_list.html.twig', ['invoices' => $invoices,]);
    }

    #[Route('/quotations', name: 'customer_quotations')]
    public function quotations_list(CompanyRepository $companyRepository): Response
    {
        $user = $this->security->getUser();
        $quotations = $companyRepository->getQuotations($user->getCompany()->getId());

        return $this->render('back/customer/quotations_list.html.twig', ['quotations' => $quotations,]);
    }

    #[Route('/new', name: 'customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $user->setCompany($this->security->getUser()->getCompany());

        $form = $this->createForm(CustomerType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger vers la liste des utilisateurs après la création
            return $this->redirectToRoute('back_customer_index');
        }

        return $this->render('back/customer/new.html.twig', ['user' => $user, 'form' => $form->createView(),]);
    }

    #[Route('/{id}', name: 'customer_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('back/customer/show.html.twig', ['user' => $user,]);
    }

    #[Route('/{id}/edit', name: 'customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CustomerType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('back_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/customer/edit.html.twig', ['user' => $user, 'form' => $form->createView(),]);
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

        return $this->redirectToRoute('back_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}


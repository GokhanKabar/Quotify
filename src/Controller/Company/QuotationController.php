<?php

namespace App\Controller\Company;

use App\Entity\Quotation;
use App\Form\QuotationType;
use App\Repository\QuotationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Repository\CompanyRepository;

#[Route('/quotation')]
class QuotationController extends AbstractController
{
    #[Route('/', name: 'quotation_index')]
    public function index(CompanyRepository $companyRepository, Security $security): Response
    {
        $user = $security->getUser();
        $quotations = $companyRepository->getQuotations($user->getCompany()->getId());

        return $this->render('company/quotation/quotations_list.html.twig', [
            'quotations' => $quotations,
        ]);
    }

    #[Route('/new', name: 'quotation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $quotation = new Quotation();
        $company = $security->getUser()->getCompany();

        $quotation->setCreationDate(new \DateTime());

        $form = $this->createForm(QuotationType::class, $quotation, [
            'company_id' => $company->getId(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quotation);
            $entityManager->flush();

            return $this->redirectToRoute('company_quotation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/quotation/new.html.twig', [
            'quotation' => $quotation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'quotation_show', methods: ['GET'])]
    public function show(Quotation $quotation): Response
    {
        return $this->render('company/quotation/show.html.twig', [
            'quotation' => $quotation,
        ]);
    }

    #[Route('/{id}/edit', name: 'quotation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quotation $quotation, EntityManagerInterface $entityManager, Security $security): Response
    {
        $company = $security->getUser()->getCompany();

        $form = $this->createForm(QuotationType::class, $quotation, [
            'company_id' => $company->getId(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quotation);
            $entityManager->flush();

            return $this->redirectToRoute('company_quotation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/quotation/edit.html.twig', [
            'quotation' => $quotation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'quotation_delete', methods: ['POST'])]
    public function delete(Request $request, Quotation $quotation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quotation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quotation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('company_quotation_index', [], Response::HTTP_SEE_OTHER);
    }
}

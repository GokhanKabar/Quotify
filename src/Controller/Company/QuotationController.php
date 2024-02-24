<?php

namespace App\Controller\Company;

use App\Entity\Quotation;
use App\Form\QuotationType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nucleos\DompdfBundle\Wrapper\DompdfWrapperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\QuotationRepository;
use App\Entity\Invoice;
use App\Entity\InvoiceDetail;

#[Route('/quotation')]
class QuotationController extends AbstractController
{
    #[Route('/', name: 'quotation_index')]
    public function index(CompanyRepository $companyRepository, Security $security): Response
    {
        $user = $security->getUser();
        $quotations = $companyRepository->getQuotations($user->getCompany()->getId());

        return $this->render('company/quotation/quotations_list.html.twig', ['quotations' => $quotations,]);
    }

    #[Route('/new', name: 'quotation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $quotation = new Quotation();
        $company = $security->getUser()->getCompany();

        $quotation->setCreationDate(new \DateTime());
        $quotation->setStatus('En attente');

        $form = $this->createForm(QuotationType::class, $quotation, [
            'company_id' => $company->getId(),
        ]);
      
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quotation);
            $entityManager->flush();

            return $this->redirectToRoute('company_quotation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/quotation/new.html.twig', ['quotation' => $quotation, 'form' => $form,]);
    }

    #[Route('/{id}', name: 'quotation_show', methods: ['GET'])]
    public function show(Quotation $quotation): Response
    {
        return $this->render('company/quotation/show.html.twig', ['quotation' => $quotation,]);
    }

    #[Route('/{id}/edit', name: 'quotation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quotation $quotation, EntityManagerInterface $entityManager, Security $security): Response
    {
        $company = $security->getUser()->getCompany();

        $form = $this->createForm(QuotationType::class, $quotation, ['company_id' => $company->getId(),]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quotation);
            $entityManager->flush();

            return $this->redirectToRoute('company_quotation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/quotation/edit.html.twig', ['quotation' => $quotation, 'form' => $form,]);
    }

    #[Route('/{id}', name: 'quotation_delete', methods: ['POST'])]
    public function delete(Request $request, Quotation $quotation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quotation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quotation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('company_quotation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pdf/{id}', name: 'quotation_pdf', methods: ['GET'])]
    public function generatePdf(Quotation $quotation, DompdfWrapperInterface $dompdfWrapper): Response
    {
        $html = $this->renderView('company/quotation/pdf.html.twig', ['quotation' => $quotation,]);

        return $dompdfWrapper->getStreamResponse($html, "quotation-{$quotation->getId()}.pdf", ['Attachment' => true,]);
    }

    #[Route('/convert/{id}', name: 'quotation_convert', methods: ['GET'])]
    public function convertQuotationToInvoice(int $id, QuotationRepository $quotationRepository, EntityManagerInterface $entityManager, Security $security) {
        $quotation = $quotationRepository->find($id);
        $company = $security->getUser()->getCompany();

        $invoice = new Invoice();
        $invoice->setCreationDate($quotation->getCreationDate());
        $invoice->setPaymentStatus($quotation->getStatus());
        $invoice->setUserReference($quotation->getUserReference());
        $invoice->setTotalHT($quotation->getTotalHT());
        $invoice->setTotalTTC($quotation->getTotalTTC());

        // On supprime le devis
        $entityManager->remove($quotation);

        foreach ($quotation->getQuotationDetails() as $quotationDetail) {
            $invoiceDetail = new InvoiceDetail();
            $invoiceDetail->setProduct($quotationDetail->getProduct());
            $invoiceDetail->setQuantity($quotationDetail->getQuantity());
            $invoiceDetail->setTva($quotationDetail->getTva());
    
            $invoice->addInvoiceDetail($invoiceDetail);
            $entityManager->persist($invoiceDetail);

            // on supprime le/les détails du/des devis
            $entityManager->remove($quotationDetail);
        }

        $entityManager->persist($invoice);
        $entityManager->flush();

        return $this->redirectToRoute('company_invoice_index', [], Response::HTTP_SEE_OTHER);
        
    }
}

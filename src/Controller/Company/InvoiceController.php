<?php

namespace App\Controller\Company;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nucleos\DompdfBundle\Wrapper\DompdfWrapperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Dompdf\Options;
use Dompdf\Dompdf;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/invoice')]
class InvoiceController extends AbstractController
{
    #[Route('/', name: 'invoice_index')]
    public function index(CompanyRepository $companyRepository, Security $security): Response
    {
        $user = $security->getUser();
        $invoices = $companyRepository->getInvoices($user->getCompany()->getId());

        return $this->render('company/invoice/invoices_list.html.twig', ['invoices' => $invoices,]);
    }

    #[Route('/new', name: 'invoice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $invoice = new Invoice();
        $company = $security->getUser()->getCompany();

        $invoice->setCreationDate(new \DateTime());
        $invoice->setPaymentStatus('En attente');

        $form = $this->createForm(InvoiceType::class, $invoice, [
            'company_id' => $company->getId(),
        ]);
      
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('company_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/invoice/new.html.twig', ['invoice' => $invoice, 'form' => $form,]);
    }

    #[Route('/{id}', name: 'invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice): Response
    {
        return $this->render('company/invoice/show.html.twig', ['invoice' => $invoice,]);
    }

    #[Route('/{id}/edit', name: 'invoice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $entityManager, Security $security): Response
    {
        $company = $security->getUser()->getCompany();

        $form = $this->createForm(InvoiceType::class, $invoice, ['company_id' => $company->getId(),]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('company_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/invoice/edit.html.twig', ['invoice' => $invoice, 'form' => $form,]);
    }

    #[Route('/{id}', name: 'invoice_delete', methods: ['POST'])]
    public function delete(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $invoice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('company_invoice_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pdf/{id}', name: 'invoice_pdf', methods: ['GET'])]
    public function generatePdf(Invoice $invoice, DompdfWrapperInterface $dompdfWrapper, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        // Configuration de Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instanciation de Dompdf
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView('company/invoice/pdf.html.twig', ['invoice' => $invoice,]);

        // Génération du PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Chemin où le PDF sera sauvegardé
        $pdfFilePath = $this->getParameter('invoice_directory') . "/invoice-{$invoice->getId()}.pdf";

        // Stockage du PDF
        file_put_contents($pdfFilePath, $dompdf->output());

        // Envoi du PDF par e-mail
        $email = (new Email())
        ->from('no-reply@quotify.fr')
        ->to($invoice->getUserReference()->getEmail())
        ->subject("Facture n°{$invoice->getId()}")
        ->text('Vous trouverez ci-joint la facture demandée.')
        ->attachFromPath($pdfFilePath, "invoice-{$invoice->getId()}.pdf");

        $mailer->send($email);

        $invoice->setPaymentStatus('Facture envoyée');

        // Enregistrez les modifications dans la base de données
        $entityManager->persist($invoice);
        $entityManager->flush();

        return $dompdfWrapper->getStreamResponse($html, "invoice-{$invoice->getId()}.pdf", ['Attachment' => true,]);
    }
}

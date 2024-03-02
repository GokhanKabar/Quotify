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
use Symfony\Component\Mailer\MailerInterface;
use Dompdf\Options;
use Dompdf\Dompdf;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Form\ProductType;
use App\Entity\Product;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


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
 
        if (!$security->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $company = $security->getUser()->getCompany();

        $quotation->setCreationDate(new \DateTime());
        $quotation->setStatus('En attente');

        $form = $this->createForm(QuotationType::class, $quotation, [
            'company_id' => $company->getId(),
        ]);
      
        $form->handleRequest($request);

        $product = new Product();
        $formProduct = $this->createForm(ProductType::class, $product, [
            'company_id' => $company->getId(),
        ]);

        $formProduct->handleRequest($request);

        $product->setCompanyReference($company);

        if ($form->isSubmitted() && $form->isValid()) {
            $totalHT = 0;
            $totalTTC = 0;    

            $details = $form->getData()->getQuotationDetails();
            
            foreach ($details as $detail) {
                $unitPrice = $detail->getProduct()->getUnitPrice();
                $quantity = $detail->getQuantity();
    
                $amountHT = $unitPrice * $quantity;
                $totalHT += $amountHT;
    
                $amountTTC = $amountHT * (1 + ($detail->getTva() / 100));
                $totalTTC += $amountTTC;
            }
    
            $quotation->setTotalHT($totalHT);
            $quotation->setTotalTTC($totalTTC);

            $entityManager->persist($quotation);
            $entityManager->flush();

            return $this->redirectToRoute('company_quotation_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('company_quotation_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/quotation/new.html.twig', [
            'quotation' => $quotation, 
            'form' => $form,
            'formProduct' => $formProduct,
        ]);
    }

    #[Route('/{id}', name: 'quotation_show', methods: ['GET'])]
    public function show(Quotation $quotation): Response
    {
        $this->denyAccessUnlessGranted('QUOTATION_VIEW', $quotation);

        return $this->render('company/quotation/show.html.twig', ['quotation' => $quotation]);
    }

    #[Route('/{id}/edit', name: 'quotation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quotation $quotation, EntityManagerInterface $entityManager, Security $security): Response
    {
        $this->denyAccessUnlessGranted('QUOTATION_EDIT', $quotation);

        if (!$security->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        $company = $security->getUser()->getCompany();

        $form = $this->createForm(QuotationType::class, $quotation, ['company_id' => $company->getId(),]);
        $form->handleRequest($request);

        $product = new Product();
        $formProduct = $this->createForm(ProductType::class, $product, [
            'company_id' => $company->getId(),
        ]);

        $formProduct->handleRequest($request);

        $product->setCompanyReference($company);

        if ($form->isSubmitted() && $form->isValid()) {
            $totalHT = 0;
            $totalTTC = 0;    
            
            $details = $form->getData()->getQuotationDetails();

            foreach ($details as $detail) {
                $unitPrice = $detail->getProduct()->getUnitPrice();
                $quantity = $detail->getQuantity();
    
                $amountHT = $unitPrice * $quantity;
                $totalHT += $amountHT;
    
                $amountTTC = $amountHT * (1 + ($detail->getTva() / 100));
                $totalTTC += $amountTTC;
            }
    
            $quotation->setTotalHT($totalHT);
            $quotation->setTotalTTC($totalTTC);

            $entityManager->persist($quotation);
            $entityManager->flush();

            return $this->redirectToRoute('company_quotation_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('company_quotation_edit', ['id' => $quotation->getId()]);
        }

        return $this->render('company/quotation/edit.html.twig', [
            'quotation' => $quotation, 
            'form' => $form,
            'formProduct' => $formProduct,
        ]);
    }

    #[Route('/{id}', name: 'quotation_delete', methods: ['POST'])]
    public function delete(Request $request, Quotation $quotation, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('QUOTATION_DELETE', $quotation);

        if ($this->isCsrfTokenValid('delete' . $quotation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quotation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('company_quotation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pdf/{id}', name: 'quotation_pdf', methods: ['GET'])]
    public function generatePdf(Quotation $quotation, DompdfWrapperInterface $dompdfWrapper, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('QUOTATION_VIEW', $quotation);

        // Configuration de Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instanciation de Dompdf
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView('company/quotation/pdf.html.twig', ['quotation' => $quotation,]);

        // Génération du PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfFilePath = $this->getParameter('quotation_directory') . "/quotation-{$quotation->getId()}.pdf";

        // Stockage du PDF
        file_put_contents($pdfFilePath, $dompdf->output());

        // Envoi du PDF par e-mail
        $email = (new TemplatedEmail())
        ->from(new Address('no-reply@quotify.fr', 'Quotify'))
        ->to($quotation->getUserReference()->getEmail())
        ->subject("Devis n°{$quotation->getId()}")
        ->htmlTemplate('company/quotation/email.html.twig')
        ->context([
            'quotation' => $quotation,
        ])
        ->attachFromPath($pdfFilePath, "quotation-{$quotation->getId()}.pdf");

    $mailer->send($email);

        // Mise à jour du statut du devis avec la date d'envoi
        $dateSent = new \DateTime(); // Obtient la date actuelle
        $formattedDate = $dateSent->format('d/m/Y H:i:s'); // Formate la date
        $quotation->setStatus("Devis envoyé par mail le " . $formattedDate);

        // Enregistrez les modifications dans la base de données
        $entityManager->persist($quotation);
        $entityManager->flush();

        return $dompdfWrapper->getStreamResponse($html, "quotation-{$quotation->getId()}.pdf", ['Attachment' => true,]);
    }

    #[Route('/convert/{id}', name: 'quotation_convert', methods: ['GET'])]
    public function convertQuotationToInvoice(int $id, QuotationRepository $quotationRepository, EntityManagerInterface $entityManager): Response
    {
        $quotation = $quotationRepository->find($id);
        $this->denyAccessUnlessGranted('QUOTATION_VIEW', $quotation);

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

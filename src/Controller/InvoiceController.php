<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\File;
use App\Form\FileType;
use App\Form\InvoiceType;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/invoice')]
class InvoiceController extends AbstractController
{
    #[Route('/', name: 'app_invoice_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        return $this->render('invoice/index.html.twig', [
            'invoices' => $invoiceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_invoice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invoice = new Invoice();
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        $uploadedFile = $form->get('file')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($uploadedFile as $fileUpload) {
                if ($fileUpload) {
                    $file = new File();

                    $originalFilename = pathinfo($fileUpload->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = $originalFilename.'-'.uniqid().'.'.$fileUpload->guessExtension();

                    try {
                        $file->setSize($fileUpload->getSize());
                        $file->setPath('documents/invoices/' . $newFilename);
                        $file->setExtension($fileUpload->guessExtension());
                        $file->setType($fileUpload->getMimeType());
                        $file->setName($fileUpload->getClientOriginalName());

                        $fileUpload->move(
                            $this->getParameter('invoice_directory'),
                            $newFilename
                        );

                        $invoice->addFile($file);
                    } catch (FileException $e) {
                        // Gérer les erreurs liées au téléchargement du fichier
                        throw $e;
                    }
                }

                $entityManager->persist($file);
            }

            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invoice/new.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice): Response
    {
        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_invoice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('file')->getData();

            foreach ($uploadedFile as $fileUpload) {
                if ($fileUpload) {
                    $file = new File();

                    $originalFilename = pathinfo($fileUpload->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = $originalFilename.'-'.uniqid().'.'.$fileUpload->guessExtension();

                    try {
                        $file->setSize($fileUpload->getSize());
                        $file->setPath('documents/invoices/' . $newFilename);
                        $file->setExtension($fileUpload->guessExtension());
                        $file->setType($fileUpload->getMimeType());
                        $file->setName($fileUpload->getClientOriginalName());

                        $fileUpload->move(
                            $this->getParameter('invoice_directory'),
                            $newFilename
                        );

                        $invoice->addFile($file);
                    } catch (FileException $e) {
                        throw $e;
                    }
                }

                $entityManager->persist($file);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_delete', methods: ['POST'])]
    public function delete(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invoice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
    }
}

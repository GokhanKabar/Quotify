<?php

namespace App\Controller;

use App\Entity\Quotation;
use App\Form\QuotationType;
use App\Repository\QuotationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\File;

#[Route('/quotation')]
class QuotationController extends AbstractController
{
    #[Route('/', name: 'app_quotation_index', methods: ['GET'])]
    public function index(QuotationRepository $quotationRepository): Response
    {
        return $this->render('quotation/index.html.twig', [
            'quotations' => $quotationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_quotation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quotation = new Quotation();
        $form = $this->createForm(QuotationType::class, $quotation);
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
                        $file->setPath('documents/quotations/' . $newFilename);
                        $file->setExtension($fileUpload->guessExtension());
                        $file->setType($fileUpload->getMimeType());
                        $file->setName($fileUpload->getClientOriginalName());
    
                        $fileUpload->move(
                            $this->getParameter('quotation_directory'),
                            $newFilename
                        );
    
                        $quotation->addFile($file);
                    } catch (FileException $e) {
                        throw $e;
                    }
                }
    
                $entityManager->persist($file);
            }

            $entityManager->persist($quotation);
            $entityManager->flush();

            return $this->redirectToRoute('app_quotation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quotation/new.html.twig', [
            'quotation' => $quotation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quotation_show', methods: ['GET'])]
    public function show(Quotation $quotation): Response
    {
        return $this->render('quotation/show.html.twig', [
            'quotation' => $quotation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_quotation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quotation $quotation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuotationType::class, $quotation);
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
                        $file->setPath('documents/quotations/' . $newFilename);
                        $file->setExtension($fileUpload->guessExtension());
                        $file->setType($fileUpload->getMimeType());
                        $file->setName($fileUpload->getClientOriginalName());
    
                        $fileUpload->move(
                            $this->getParameter('quotation_directory'),
                            $newFilename
                        );
    
                        $quotation->addFile($file);
                    } catch (FileException $e) {
                        // Gérer les erreurs liées au téléchargement du fichier
                        throw $e;
                    }
                }
    
                $entityManager->persist($file);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_quotation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quotation/edit.html.twig', [
            'quotation' => $quotation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quotation_delete', methods: ['POST'])]
    public function delete(Request $request, Quotation $quotation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quotation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quotation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quotation_index', [], Response::HTTP_SEE_OTHER);
    }
}

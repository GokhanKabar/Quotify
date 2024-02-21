<?php

namespace App\Controller\Company;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Repository\CompanyRepository;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'product_index', methods: ['GET'])]
    public function index(CompanyRepository $companyRepository, Security $security): Response
    {
        $user = $security->getUser();
        $company = $user->getCompany()->getId();

        return $this->render('company/product/index.html.twig', [
            'products' => $companyRepository->getProducts($company)
        ]);
    }

    #[Route('/new', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $product = new Product();
        $company = $security->getUser()->getCompany();

        $form = $this->createForm(ProductType::class, $product, [
            'company_id' => $company->getId(),
        ]);
        $form->handleRequest($request);

        // set user company reference to the product to limit the products to the company
        $product->setCompanyReference($company);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('company_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('company/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('company_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('company_product_index', [], Response::HTTP_SEE_OTHER);
    }
}

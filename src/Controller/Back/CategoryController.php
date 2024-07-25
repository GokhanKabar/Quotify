<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10; // Ou une autre limite selon vos préférences

        $categories = $categoryRepository->findCategoriesByPage($page, $limit);

        return $this->render('back/category/index.html.twig', [
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => ceil(count($categories) / $limit),
        ]);
    }

    #[Route('/new', name: 'category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('back_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('back/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('back_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($category);
                $entityManager->flush();
                $this->addFlash('success', 'La catégorie a été supprimée avec succès.');
            } catch (ForeignKeyConstraintViolationException $e) {
                // Ajoute un message flash pour informer l'utilisateur de l'erreur
                $this->addFlash('error', 'Cette catégorie ne peut pas être supprimée car elle contient des produits associés.');
            } catch (\Exception $e) {
                // Gestion d'autres types d'exceptions si nécessaire
                $this->addFlash('error', 'Une erreur est survenue lors de la suppression de la catégorie.');
            }
        }
    
        return $this->redirectToRoute('back_category_index', [], Response::HTTP_SEE_OTHER);
    }
}

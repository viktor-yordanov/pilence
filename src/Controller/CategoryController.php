<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categories/menu', name: 'categories_menu')]
    public function menu(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/_menu.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/{id}', name: 'category_view')]
    public function view(int $id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);
        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        return $this->render('category/view.html.twig', [
            'category' => $category,
            'projects' => $category->getProjects(),
        ]);
    }
}

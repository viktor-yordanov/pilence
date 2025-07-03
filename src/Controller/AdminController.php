<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Project;
use App\Form\AddProjectForm;
use App\Form\CategoryForm;
use App\Repository\CategoryRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class AdminController extends AbstractController
{
    #[Route('/', name: 'admin')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/home', name: 'admin_manage_homepage')]
    public function manageHomepage(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Logic to manage homepage, e.g., updating content, banners, etc.

        return $this->render('admin/manage_homepage.html.twig');
    }

    #[Route('/categories', name: 'admin_manage_categories')]
    public function manageCategories(CategoryRepository $categoryRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $categories = $categoryRepository->findAll();

        return $this->render('admin/manage_categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categories/new', name: 'admin_add_category')]
    public function addCategory(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $category = new Category();
        $form = $this->createForm(CategoryForm::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Category created!');
            return $this->redirectToRoute('admin_add_category');
        }

        return $this->render('admin/add_category.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/categories/{id}', name: 'admin_edit_category')]
    public function manageCategory(Category $category): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/manage_category.html.twig', [
            'category' => $category,
            'projects' => $category->getProjects(),
        ]);
    }

    #[Route('/projects', name: 'admin_manage_projects')]
    public function manageProjects(ProjectRepository $projectRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $projects = $projectRepository->findAll();

        return $this->render('admin/manage_projects.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/projects/new', name: 'admin_add_project')]
    public function addProject(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $project = new Project();
        $project->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(AddProjectForm::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($project);
            $em->flush();

            $this->addFlash('success', 'Project created!');
            return $this->redirectToRoute('admin_add_project');
        }

        return $this->render('admin/add_project.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/projects/{id}', name: 'admin_edit_project')]
    public function manageProject(Project $project): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/manage_project.html.twig', [
            'project' => $project,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Project;
use App\Entity\Image;
use App\Form\ImageUploadForm;
use App\Form\AddProjectForm;
use App\Form\CategoryForm;
use App\Repository\CategoryRepository;
use App\Repository\ProjectRepository;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class AdminController extends AbstractController
{
    public function __construct(
        #[Autowire('%image_directory%')] private readonly string $imageDirectory,
        private readonly SluggerInterface $slugger
    ) {
    }
    #[Route('/', name: 'admin')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/index.html.twig');
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

    #[Route('/categories/{id}/edit', name: 'admin_edit_category')]
    public function editCategory(Request $request, Category $category, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(CategoryForm::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Category updated');

            return $this->redirectToRoute('admin_manage_categories');
        }

        return $this->render('admin/manage_category.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    #[Route('/categories/{id}/delete', name: 'admin_delete_category', methods: ['POST'])]
    public function deleteCategory(Request $request, Category $category, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete_category'.$category->getId(), $request->request->get('_token'))) {
            $em->remove($category);
            $em->flush();
            $this->addFlash('success', 'Category deleted');
        }

        return $this->redirectToRoute('admin_manage_categories');
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

    #[Route('/projects/{id}/edit', name: 'admin_edit_project')]
    public function editProject(Request $request, Project $project, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(AddProjectForm::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Project updated');

            return $this->redirectToRoute('admin_manage_projects');
        }

        return $this->render('admin/manage_project.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/projects/{id}/delete', name: 'admin_delete_project', methods: ['POST'])]
    public function deleteProject(Request $request, Project $project, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete_project'.$project->getId(), $request->request->get('_token'))) {
            $em->remove($project);
            $em->flush();
            $this->addFlash('success', 'Project deleted');
        }

        return $this->redirectToRoute('admin_manage_projects');
    }

    #[Route('/projects/{id}/images', name: 'admin_project_images')]
    public function projectImages(Project $project, ImageRepository $imageRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/project_images.html.twig', [
            'project' => $project,
            'images' => $imageRepository->findBy(['project' => $project]),
        ]);
    }

    #[Route('/projects/{id}/images/upload', name: 'admin_project_upload_image')]
    public function uploadImage(Request $request, Project $project, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $image = new Image();
        $form = $this->createForm(ImageUploadForm::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move($this->imageDirectory, $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Could not upload image');
                }

                $image->setFilename($newFilename);
            }

            $image->setProject($project);
            $image->setCreatedAt(new \DateTime());
            $em->persist($image);
            $em->flush();

            $this->addFlash('success', 'Image uploaded');

            return $this->redirectToRoute('admin_project_images', ['id' => $project->getId()]);
        }

        return $this->render('admin/upload_image.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/images/{id}/delete', name: 'admin_delete_image', methods: ['POST'])]
    public function deleteImage(Request $request, Image $image, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete_image'.$image->getId(), $request->request->get('_token'))) {
            $em->remove($image);
            $em->flush();
            $this->addFlash('success', 'Image removed');
        }

        return $this->redirectToRoute('admin_project_images', ['id' => $image->getProject()->getId()]);
    }
}

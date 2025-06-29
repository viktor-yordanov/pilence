<?php

namespace App\Controller;

use App\Form\ProjectForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;

class ProjectsController extends AbstractController
{
    #[Route('/projects', name: 'projects')]
    public function index(ProjectRepository $projectRepository): Response
    {
        // Fetch projects from the database or service
        $projects = $projectRepository->findAll();

        return $this->render('projects/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/projects/new', name: 'project_new')]
    public function new(Request $request, ProjectRepository $projectRepository): Response
    {
        $form = $this->createForm(ProjectForm::class);

        // Handle the form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Process the form data, e.g., save the project to the database
            $project = $form->getData();
            // Assuming you have a service or repository to handle saving
            $projectRepository->add($project, true);

            // Redirect to the project list or detail page after saving
            return $this->redirectToRoute('projects');
        }

        // Render a form to create a new project
        return $this->render('projects/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/projects/{id}', name: 'project_details')]
    public function detail(int $id, ProjectRepository $projectRepository): Response
    {
        // Fetch a single project by ID
        $project = $projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Project not found');
        }

        return $this->render('projects/details.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/projects/{id}/edit', name: 'project_edit')]
    public function edit(int $id, ProjectRepository $projectRepository): Response
    {
        // Fetch the project to edit
        $project = $projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Project not found');
        }

        $form = $this->createForm(ProjectForm::class, $project);

        // Render a form to edit the project
        return $this->render('projects/edit.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProjectRepository;

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

    #[Route('/projects/{id}', name: 'project_details')]
    public function detail(int $id, ProjectRepository $projectRepository): Response
    {
        // Fetch a single project by ID
        $project = $projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Project not found');
        }

        return $this->render('projects/detail.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/projects/new', name: 'project_new')]
    public function new(): Response
    {
        // Render a form to create a new project
        return $this->render('projects/new.html.twig');
    }
}

<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\Project1Type;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Pdf;


#[Route('/project')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_project_index', methods: ['GET'])]
    public function index(Request $request, ProjectRepository $projectRepository): Response
    {
        $searchQuery = $request->query->get('search');
        $sortBy = $request->query->get('sortBy');

        if ($sortBy === 'name') {
            $projects = $projectRepository->findAllSortedByName();
        } else {
            $projects = $projectRepository->findBySearchQuery($searchQuery);
        }

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }
    #[Route('/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(Project1Type::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_project_show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Project1Type::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_project_delete', methods: ['POST'])]
    public function delete(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/project/history', name: 'app_project_history', methods: ['GET'])]
    public function history(ProjectRepository $projectRepository): Response
    {
        // Fetch projects from the repository
        $projects = $projectRepository->findAll();

        return $this->render('project/history.html.twig', [
            'projects' => $projects,
        ]);
    }
    #[Route('/project/{id}/pdf', name: 'app_project_pdf', methods: ['GET'])]
    public function generatePdf(Pdf $pdf, Project $project): Response
{
    $html = $this->renderView('project/pdf_template.html.twig', [
        'project' => $project,
    ]);

    $filename = 'project_' . $project->getId() . '.pdf';

    // Generate PDF from HTML
    $pdf->generateFromHtml($html, $filename);

    // Alternatively, you can return a response
    return new Response($pdf->getOutputFromHtml($html), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="'.$filename.'"'
    ]);
}
}

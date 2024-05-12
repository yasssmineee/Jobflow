<?php

namespace App\Controller;

use App\Entity\Societe;
use App\Form\SocieteType;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MailerService;

#[Route('/societe')]
class SocieteController extends AbstractController
{
    #[Route('/', name: 'app_societe_index', methods: ['GET', 'POST'])]
    public function index(SocieteRepository $societeRepository, Request $request): Response
    {
        $sortCriteria = $request->request->get('sort');

        if ($sortCriteria === 'nom') {
            $societes = $societeRepository->findBy([], ['nom' => 'ASC']);
        } elseif ($sortCriteria === 'secteur') {
            $societes = $societeRepository->findBy([], ['secteur' => 'ASC']);
        } else {
            $societes = $societeRepository->findAll();
        }

        $searchTerm = $request->query->get('q');
        if ($searchTerm) {
            $societes = $societeRepository->findBySearchTerm($searchTerm);
        }

        $stats = $societeRepository->getStatistiques();

        return $this->render('societe/index.html.twig', [
            'societes' => $societes,
            'searchTerm' => $searchTerm,
            'stats' => $stats,
        ]);
    }
    #[Route('/search', name: 'search', methods: ['GET'])]    
    public function search(SocieteRepository $societeRepository, Request $request): Response
{
   
    $searchTerm = $request->query->get('search');
   
    if ($searchTerm) {
        $societes = $societeRepository->findBySearchTerm($searchTerm);
    } else {
        $societes = $societeRepository->findAll();
    }

    return $this->render('societe/_search_results.html.twig', [
        'societes' => $societes,
        'searchTerm' => $searchTerm,
    ]);
}
    #[Route('/new', name: 'app_societe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,MailerService $mailer): Response
    {
        $societe = new Societe();
        $form = $this->createForm(SocieteType::class, $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($societe);
            $entityManager->flush();

            return $this->redirectToRoute('app_societe_index', [], Response::HTTP_SEE_OTHER);
            $mailer->sendEmail('rayenfarhani9@gmail.com.com', 'Email content', 'Email subject'); // Assuming you want to send an email after persisting the entity


        }

        return $this->renderForm('societe/new.html.twig', [
            'societe' => $societe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_societe_show', methods: ['GET'])]
    public function show(Societe $societe): Response
    {
        return $this->render('societe/show.html.twig', [
            'societe' => $societe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_societe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Societe $societe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SocieteType::class, $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_societe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('societe/edit.html.twig', [
            'societe' => $societe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_societe_delete', methods: ['POST'])]
    public function delete(Request $request, Societe $societe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$societe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($societe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_societe_index', [], Response::HTTP_SEE_OTHER);
    }
    public function statsEvenements(SocieteRepository $societeRepository): Response
    {
        
  $stats = $societeRepository->getStatistiques();


        return $this->render('societe/index.html.twig', [
            
            'stats' => $stats,
        ]);
    }
}
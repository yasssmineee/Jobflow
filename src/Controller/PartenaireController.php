<?php

namespace App\Controller;

use App\Entity\Partenaire;
use App\Form\PartenaireType;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/partenaire')]
class PartenaireController extends AbstractController
{
    #[Route('/', name: 'app_partenaire_index', methods: ['GET','POST'])]
    public function index(Request $request, PartenaireRepository $partenaireRepository): Response
    {
        $sortCriteria = $request->request->get('sort');
    
        // Récupérer les partenaires en fonction des critères de tri
        if ($sortCriteria === 'nom') {
            $partenaires = $partenaireRepository->findBy([], ['nom' => 'ASC']);
        } elseif ($sortCriteria === 'duree') {
            $partenaires = $partenaireRepository->findBy([], ['duree' => 'ASC']);
        } elseif ($sortCriteria === 'description') {
            $partenaires = $partenaireRepository->findBy([], ['description' => 'ASC']);
        } else {
            // Aucun tri spécifié, afficher tous les partenaires
            $partenaires = $partenaireRepository->findAll();
        }
    
        $searchTerm = $request->query->get('q');
        if ($searchTerm) {
            // Filtrer les partenaires en fonction du terme de recherche
            $partenaires = $partenaireRepository->findBySearchTerm($searchTerm);
        }
        $stats = $partenaireRepository->getStatistiques();

        return $this->render('partenaire/index.html.twig', [
            'partenaires' => $partenaires,
            'searchTerm' => $searchTerm,
            'stats' => $stats,

        ]);
    } #[Route('/search', name: 'searchP', methods: ['GET'])]    
    public function search(PartenaireRepository $partenaireRepository, Request $request): Response
{
   
    $searchTerm = $request->query->get('search');
   
    if ($searchTerm) {
        $partenaires = $partenaireRepository->findBySearchTerm($searchTerm);
    } else {
        $partenaires = $partenaireRepository->findAll();
    }

    return $this->render('partenaire/_search_results.html.twig', [
        'partenaires' => $partenaires,
        'searchTerm' => $searchTerm,

    ]);
}
    

    #[Route('/new', name: 'app_partenaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $partenaire = new Partenaire();
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($partenaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partenaire/new.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_partenaire_show', methods: ['GET'])]
    public function show(Partenaire $partenaire): Response
    {
        return $this->render('partenaire/show.html.twig', [
            'partenaire' => $partenaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_partenaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Partenaire $partenaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partenaire/edit.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_partenaire_delete', methods: ['POST'])]
    public function delete(Request $request, Partenaire $partenaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partenaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($partenaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }
    public function statsEvenements(PartenaireRepository $partenaireRepository): Response
    {
        
  $stats = $partenaireRepository->getStatistiques();


        return $this->render('partenaire/index.html.twig', [
            
            'stats' => $stats,
        ]);
    }
   
}
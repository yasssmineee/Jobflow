<?php

namespace App\Controller;

use App\Entity\Opportunite;
use App\Form\OpportuniteSearchType;
use App\Form\OpportuniteType;
use App\Repository\OpportuniteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/opportunite')]
class OpportuniteController extends AbstractController
{
    #[Route('/', name: 'app_opportunite_index', methods: ['GET'])]
    public function index(Request $request,OpportuniteRepository $opportuniteRepository): Response
    {

        // filter by opportunite name 
        $form = $this->createForm(OpportuniteSearchType::class);
        $form->handleRequest($request);
        $opp = $opportuniteRepository->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $opp = $opportuniteRepository->search($data['q'], $data['type'], $data['sort_by']);
        }
        // get the current route name
        $routeName=$request->attributes->get('_route');  
        return $this->render(
            'opportunite/index.html.twig',
            array(
                'form' => $form->createView(),
                'opportunites' => $opp,
                'q' => $data['q'] ?? null,
                'type' => $data['type'] ?? null,
                'sort_by' => $data['sort_by'] ?? 'name',
                'routeName' => $routeName
            )
        );
       
    }

    #[Route('/new', name: 'app_opportunite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $opportunite = new Opportunite();
        $form = $this->createForm(OpportuniteType::class, $opportunite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($opportunite);
            $entityManager->flush();
            $this->addFlash('success', 'User activated successfully');

            return $this->redirectToRoute('app_opportunite_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('opportunite/new.html.twig', [
            'opportunite' => $opportunite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_opportunite_show', methods: ['GET'])]
    public function show(Opportunite $opportunite): Response
    {
        return $this->render('opportunite/show.html.twig', [
            'opportunite' => $opportunite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_opportunite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Opportunite $opportunite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OpportuniteType::class, $opportunite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_opportunite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('opportunite/edit.html.twig', [
            'opportunite' => $opportunite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_opportunite_delete', methods: ['POST'])]
    public function delete(Request $request, Opportunite $opportunite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$opportunite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($opportunite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_opportunite_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_opportunite_edit', methods: ['GET', 'POST'])]
    public function addToFavList(Request $request, Opportunite $opportunite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OpportuniteType::class, $opportunite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_opportunite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('opportunite/edit.html.twig', [
            'opportunite' => $opportunite,
            'form' => $form,
        ]);
    }
    
}
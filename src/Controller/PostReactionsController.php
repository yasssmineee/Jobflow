<?php

namespace App\Controller;

use App\Entity\PostReactions;
use App\Form\PostReactionsType;
use App\Repository\PostReactionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post/reactions')]
class PostReactionsController extends AbstractController
{
    #[Route('/', name: 'app_post_reactions_index', methods: ['GET'])]
    public function index(PostReactionsRepository $postReactionsRepository): Response
    {
        return $this->render('post_reactions/index.html.twig', [
            'post_reactions' => $postReactionsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_post_reactions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $postReaction = new PostReactions();
        $form = $this->createForm(PostReactionsType::class, $postReaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($postReaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_reactions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post_reactions/new.html.twig', [
            'post_reaction' => $postReaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_reactions_show', methods: ['GET'])]
    public function show(PostReactions $postReaction): Response
    {
        return $this->render('post_reactions/show.html.twig', [
            'post_reaction' => $postReaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_reactions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PostReactions $postReaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostReactionsType::class, $postReaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_reactions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post_reactions/edit.html.twig', [
            'post_reaction' => $postReaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_reactions_delete', methods: ['POST'])]
    public function delete(Request $request, PostReactions $postReaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postReaction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($postReaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_reactions_index', [], Response::HTTP_SEE_OTHER);
    }
}

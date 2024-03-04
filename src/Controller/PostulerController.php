<?php

namespace App\Controller;

use App\Entity\Postuler;
use App\Form\PostulerType;
use App\Repository\PostulerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/postuler')]
class PostulerController extends AbstractController
{
    #[Route('/', name: 'app_postuler_index', methods: ['GET'])]
    public function index(PostulerRepository $postulerRepository): Response
    {
        return $this->render('postuler/index.html.twig', [
            'postulers' => $postulerRepository->findAll(),
        ]);
    }

    #[Route('/userPostuler', name: 'app_postuler')]
    public function userProjects(PostulerRepository $repository,Request $request): Response
    {
        $postulers = $repository->findBy(['user' => $this->getUser()]);
   
        
        return $this->render('postuler/index.html.twig', [
            'postulers' => $postulers,
        ]);
    }


    #[Route('/new/{idOpportunite}', name: 'app_postuler_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine, SluggerInterface $slugger, $idOpportunite): Response
    {
        $postuler = new Postuler();
        $form = $this->createForm(PostulerType::class, $postuler);
        $postuler->setUser($this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('cv')->getData();
            // get params idOpportunite 





            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('project_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $postuler->setCv($newFilename);
            }
            $em = $doctrine->getManager();
            $entityManager->persist($postuler);
            $entityManager->flush();

            return $this->redirectToRoute('app_postuler_index', [], Response::HTTP_SEE_OTHER);
        }

   
            
        
        

        return $this->renderForm('postuler/new.html.twig', [
            'postuler' => $postuler,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_postuler_show', methods: ['GET'])]
    public function show(Postuler $postuler): Response
    {
        return $this->render('postuler/show.html.twig', [
            'postuler' => $postuler,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_postuler_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Postuler $postuler, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostulerType::class, $postuler);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_postuler_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('postuler/edit.html.twig', [
            'postuler' => $postuler,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_postuler_delete', methods: ['POST'])]
    public function delete(Request $request, Postuler $postuler, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postuler->getId(), $request->request->get('_token'))) {
            $entityManager->remove($postuler);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_postuler_index', [], Response::HTTP_SEE_OTHER);
    }
}
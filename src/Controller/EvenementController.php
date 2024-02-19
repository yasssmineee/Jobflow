<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EvenementController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_evenement')]
    public function index(): Response
    { 
        $evenements = $this->entityManager->getRepository(Evenement::class)->findAll();
        
        return $this->render('evenement/index.html.twig', [
            'e' => $evenements
        ]);
    }

    #[Route('/add_evenement', name: 'app_add_evenement')]
public function Addevenement(Request $request, EntityManagerInterface $entityManager): Response
{
    $evenement = new Evenement();
    $form = $this->createForm(EvenementType::class, $evenement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gérez le téléchargement de l'image
        $imageFile = $form->get('image')->getData();
        if ($imageFile instanceof UploadedFile) {
            // Déplacez le fichier dans le répertoire où vous souhaitez stocker les images
            $newFilename = uniqid().'.'.$imageFile->guessExtension();
            $imageFile->move(
                $this->getParameter('images_directory'),
                $newFilename
            );
            
            // Mise à jour de l'image de l'événement
            $evenement->setImage($newFilename);
        }

        // Enregistrez l'entité Evenement
        $entityManager->persist($evenement);
        $entityManager->flush();

        // Redirigez l'utilisateur
        return $this->redirectToRoute('app_evenement');
    }

    return $this->render('evenement/createEvenement.html.twig', [
        'form' => $form->createView(),
        'evenement' => $evenement,

    ]);
}

#[Route('/supp_evenement/{id}', name: 'app_supp_evenement')]
public function supprimerEvenement(Evenement $evenement, EntityManagerInterface $entityManager): Response
{
    $entityManager->remove($evenement);
    $entityManager->flush();

    return $this->redirectToRoute('app_evenement');
}

#[Route('/modifier_evenement/{id}', name: 'app_modifier_evenement')]
    public function Modifierevenement(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $evenement = $entityManager->getRepository(Evenement::class)->find($id);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement');
        }

        return $this->render('evenement/updateEvenement.html.twig', [
            'form' => $form->createView(),
            'evenement' => $evenement,
        ]);
    }
}

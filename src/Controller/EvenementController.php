<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Services\QrCodeServices;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;






class EvenementController extends AbstractController
{
    private $entityManager;
private $qrCodeServices;
    public function __construct(EntityManagerInterface $entityManager , QrCodeServices $qrCodeServices)
    {
        $this->entityManager = $entityManager;
        $this->qrCodeServices = $qrCodeServices;
    }
    // Autres attributs et méthodes
    
    

    #[Route('/', name: 'app_evenement')]
    public function index(): Response
{ 
    $evenements = $this->entityManager->getRepository(Evenement::class)->findAll();
    $qrCodes = [];

    // Générer les QR codes pour chaque événement
    foreach ($evenements as $evenement) {
        $qrCodes[$evenement->getId()] = $this->qrCodeServices->qrcode($evenement->getId());
    }
    
    return $this->render('evenement/index.html.twig', [
        'evenements' => $evenements,
        'qrCodes' => $qrCodes,
        'evenement' => $evenement,

    ]);
}

    #[Route('/add_evenement', name: 'app_add_evenement')]
    public function Addevenement(Request $request, EntityManagerInterface $entityManager, QrCodeServices $qrCodeServices): Response
    {
        $qrcode = null; 
        
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
    
            // Générez le QR code à partir de l'ID de l'événement
            $qrcode = $qrCodeServices->qrcode($evenement->getId());
    
            // Redirigez l'utilisateur
            return $this->redirectToRoute('app_evenement');
        }
    
        return $this->render('evenement/createEvenement.html.twig', [
            'form' => $form->createView(),
            'evenement' => $evenement,
            'qrcode' => $qrcode,
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
    function sortEventsByTitle($events) {
        usort($events, function($a, $b) {
            return strcmp($a->getTitre(), $b->getTitre());
        });
        return $events;
    }
    #[Route('/rechercher_evenement', name: 'app_rechercher_evenement')]
public function searchAjax(Request $request, EntityManagerInterface $entityManager): Response
{
    // Récupérer le terme de recherche de la requête Ajax
    $searchTerm = $request->query->get('q');

    // Vérifier si le terme de recherche est vide
    if (empty($searchTerm)) {
        // Chargez tous les événements si le terme de recherche est vide
        $searchResults = $entityManager->getRepository(Evenement::class)->findAll();
    } else {
        // Effectuer la recherche dans votre source de données (par exemple, une base de données)
        $searchResults = $entityManager->getRepository(Evenement::class)->createQueryBuilder('e')
            ->where('e.titre LIKE :searchTerm')
            ->setParameter('searchTerm', $searchTerm.'%') // Recherche les événements dont le titre commence par le terme de recherche
            ->getQuery()
            ->getResult();
    }

    // Convertir les résultats en tableau associatif pour l'envoi au format JSON
    $formattedResults = [];
    foreach ($searchResults as $result) {
        $formattedResults[] = [
            'titre' => $result->getTitre(),
            'localisation' => $result->getLocalisation(),
            'date' => $result->getDate()->format('Y-m-d'), // Formatage de la date
            'heure' => $result->getHeure()->format('H:i:s'), // Formatage de l'heure
            'nbparticipant' => $result->getNbParticipant(),
            'image' => $result->getImage(), // Assurez-vous que cette méthode renvoie le chemin de l'image
            'id' => $result->getId(), // Ajoutez l'ID de l'événement
            // Ajoutez d'autres champs si nécessaire
        ];
    }

    // Retourner les résultats au format JSON
    return $this->json($formattedResults);
}


   
}

 

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
use App\Repository\EvenementRepository;






class EvenementController extends AbstractController
{
    private $entityManager;
private $qrCodeServices;
    public function __construct(EntityManagerInterface $entityManager , QrCodeServices $qrCodeServices,EvenementRepository $evenementRepository)
    { 
        $this->entityManager = $entityManager;
        $this->qrCodeServices = $qrCodeServices;
    }
    // Autres attributs et méthodes
    
    

    #[Route('/', name: 'app_evenement')]
public function index(Request $request, EvenementRepository $evenementRepository): Response
{ 
    // Récupérer tous les événements
    $evenements = $evenementRepository->findAll();

    // Vérifier si le formulaire de tri a été soumis
    if ($request->isMethod('POST')) {
        $sortCriteria = $request->request->get('sort');

        // Trier les événements en fonction du critère de tri sélectionné
        if ($sortCriteria === 'titre') {
            usort($evenements, function($a, $b) {
                return $a->getTitre() <=> $b->getTitre();
            });
        } elseif ($sortCriteria === 'localisation') {
            usort($evenements, function($a, $b) {
                return $a->getLocalisation() <=> $b->getLocalisation();
            });
        } elseif ($sortCriteria === 'nbparticipant') {
            usort($evenements, function($a, $b) {
                return $a->getNbParticipant() <=> $b->getNbParticipant();
            });
        }
        
        // Ajoutez ici d'autres critères de tri si nécessaire
    }

    // Générer les QR codes pour chaque événement
    $qrCodes = [];
    foreach ($evenements as $evenement) {
        $qrCodes[$evenement->getId()] = $this->qrCodeServices->qrcode($evenement->getId());
    }
    
    // Récupérer les statistiques sur les titres des événements
    $stats = $evenementRepository->getStatistiques();

    return $this->render('evenement/index.html.twig', [
        'evenements' => $evenements,
        'qrCodes' => $qrCodes,
        'stats' => $stats,
        'evenement' => $evenement, // Assurez-vous que cela est correctement utilisé dans le template Twig
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
                ->where('e.titre LIKE :searchTerm OR e.localisation LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$searchTerm.'%') // Recherche les événements dont le titre ou la localisation contiennent le terme de recherche
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


public function statsEvenements(EvenementRepository $evenementRepository): Response
    {
        
  $stats = $evenementRepository->getStatistiques();


        return $this->render('evenement/index.html.twig', [
            
            'stats' => $stats,
        ]);
    }
   /* #[Route('/comment-statistics', name:'comment_statistics')]
    public function statics(PublicationRepository $publicationRepository): Response
    {
        $statistics = $publicationRepository->getCommentStatistics();

        return $this->render('publication/stat.html.twig', [
            'statistics' => $statistics,
        ]);
    }

*/
   
}

 

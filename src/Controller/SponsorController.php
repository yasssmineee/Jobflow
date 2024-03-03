<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Form\SponsorType;
use App\Repository\SponsorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;

class SponsorController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
   

    #[Route('/sponsor', name: 'app_sponsor')]
    public function sponsor(Request $request, SponsorRepository $sponsorRepository): Response
    {
        // Récupérer le terme de recherche de la requête HTTP
        $searchTerm = $request->query->get('q');
        
        // Récupérer le critère de tri de la requête HTTP
        $sortCriteria = $request->query->get('sort');
        
        // Charger tous les sponsors
        $sponsors = $sponsorRepository->findAll();
        
        // Trier les sponsors en fonction du critère de tri sélectionné
        if ($sortCriteria === 'nom') {
            usort($sponsors, function($a, $b) {
                return $a->getNom() <=> $b->getNom();
            });
        } elseif ($sortCriteria === 'type') {
            usort($sponsors, function($a, $b) {
                return $a->getType() <=> $b->getType();
            });
        }
        
        // Charger les statistiques des sponsors
        $stats = $sponsorRepository->getStatistiques();
    
        // Rendre la vue avec les sponsors triés, le terme de recherche et les statistiques
        return $this->render('evenement/sponsor.html.twig', [
            'sponsor' => $sponsors,
            'searchTerm' => $searchTerm,
            'stats' => $stats
        ]);
    }

    
    #[Route('/add_sponsor', name: 'app_add_sponsor')]
    public function Addsponsor(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sponsor = new Sponsor();
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sponsor);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_sponsor');
        }
    
        return $this->render('evenement/createSponsor.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/supp_sponsor/{id}', name: 'app_supp_sponsor')]
public function supprimersponsor(Sponsor $sponsor, EntityManagerInterface $entityManager): Response
{
    $entityManager->remove($sponsor);
    $entityManager->flush();

    return $this->redirectToRoute('app_sponsor');
}

#[Route('/modifier_sponsor/{id}', name: 'app_modifier_sponsor')]
    public function Modifiersponsor(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $sponsor = $entityManager->getRepository(Sponsor::class)->find($id);
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sponsor');
        }

        return $this->render('evenement/updateSponsor.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/rechercher_sponsor', name: 'app_rechercher_sponsor')]
public function searchAjax(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    // Récupérer le terme de recherche de la requête Ajax
    $searchTerm = $request->query->get('q');

    // Vérifier si le terme de recherche est vide
    if (empty($searchTerm)) {
        // Charger tous les sponsors si le terme de recherche est vide
        $searchResults = $entityManager->getRepository(Sponsor::class)->findAll();
    } else {
        // Effectuer la recherche dans votre source de données (par exemple, une base de données)
        $searchResults = $entityManager->getRepository(Sponsor::class)->createQueryBuilder('s')
            ->where('s.nom LIKE :searchTerm')
            ->setParameter('searchTerm', $searchTerm.'%') // Recherche les sponsors dont le nom commence par le terme de recherche
            ->getQuery()
            ->getResult();
    }

    // Convertir les résultats en tableau associatif pour l'envoi au format JSON
    $formattedResults = [];
    foreach ($searchResults as $result) {
        $formattedResults[] = [
            'id' => $result->getId(),
            'nom' => $result->getNom(),
            'type' => $result->getType(),
            // Ajoutez d'autres champs si nécessaire
        ];
        
    }

    // Retourner les résultats au format JSON
    return new JsonResponse($formattedResults);
}
public function statsSponsor(SponsorRepository $sponsorRepository): Response
{
    // Charger les statistiques des sponsors
    $stats = $sponsorRepository->getStatistiques();

    // Rendre la vue avec les statistiques
    return $this->render('evenement/sponsor.html.twig', [
        'stats' => $stats,
    ]);
}
}

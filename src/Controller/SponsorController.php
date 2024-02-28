<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Form\SponsorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SponsorController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/sponsor', name: 'app_sponsor')]
    public function sponsor(): Response
    {
        $sponsors = $this->entityManager->getRepository(Sponsor::class)->findAll();
        
        return $this->render('evenement/sponsor.html.twig', [
            's' => $sponsors
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
public function searchSponsor(Request $request, EntityManagerInterface $entityManager): Response
{
    // Créer le formulaire pour la barre de recherche
    $form = $this->createFormBuilder()
        ->setAction($this->generateUrl('app_rechercher_sponsor'))
        ->add('q', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Rechercher']])
        ->add('Rechercher', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']])
        ->getForm();

    // Récupérer le terme de recherche de la requête
    $searchTerm = $request->query->get('q');

    // Si un terme de recherche est fourni, effectuer la recherche par nom
    if ($searchTerm) {
        $searchResults = $entityManager->getRepository(Sponsor::class)->findByNom($searchTerm);
    } else {
        // Si aucun terme de recherche n'est fourni, afficher tous les sponsors
        $searchResults = $entityManager->getRepository(Sponsor::class)->findAll();
    }
    
    // Trie les résultats de la recherche par nom
    $searchResults = $this->sortSponsorsByNom($searchResults);
    
    return $this->render('evenement/sponsor.html.twig', [
        'form' => $form->createView(),
        'searchResults' => $searchResults,
        'searchTerm' => $searchTerm,
    ]);
}

// Fonction pour trier les sponsors par nom
private function sortSponsorsByNom($sponsors) {
    usort($sponsors, function($a, $b) {
        return strcmp($a->getNom(), $b->getNom());
    });
    return $sponsors;
}


}

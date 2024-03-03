<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;

class GoogleController extends AbstractController
{
    /**
     * @Route("/connect/google", name="connect_google")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // Rediriger vers Google pour l'authentification
        return $clientRegistry
            ->getClient('google')
            ->redirect();
    }

    /**
     * @Route("/connect/google/check", name="google_connect_check")
     */
    public function connectCheckAction(Request $request)
    {
        // Vérifier si l'utilisateur est connecté
        if ($this->getUser()) {
            // Si l'utilisateur est connecté, rediriger vers la page d'accueil
            return $this->redirectToRoute('app_home1');
        } else {
            // Si l'utilisateur n'est pas connecté, renvoyer une réponse JSON
            return $this->json(['status' => false, 'message' => 'Utilisateur non trouvé']);
        }
    }
}

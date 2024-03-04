<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\Societe;
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Security $security): Response
    {
        $user = $security->getUser();
        $username = $user ? $user->getUserIdentifier() : null;

        return $this->render('home/societelogin.html.twig', [
            'username' => $username,
        ]);
    }
    
    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
         /** @var UserInterface|null $user */
        $user = $this->getUser();

        // Check if the user is logged in and has a Societe associated with it
        if (!$user || !$user->getSociete()) {
            throw $this->createNotFoundException('User not found or no Societe associated with the user.');
        }

        // Render the profile template with the associated Societe information
        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
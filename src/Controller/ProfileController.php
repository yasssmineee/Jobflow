<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
{
    #[Route('/home1', name: 'app_home1')]
    public function index(Security $security): Response
    {
        /*return $this->render('home/aveclogin.html.twig', [
            'controller_name' => 'ProfileController',
        ]);*/
        $user = $security->getUser();
        $username = $user ? $user->getUserIdentifier() : null;

        return $this->render('home/aveclogin.html.twig', [
            'username' => $username,
        ]);
    }
}
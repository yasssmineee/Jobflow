<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class SecurityController1 extends AbstractController
{
    
        #[Route('/loginad', name: 'admin_login')]
        public function adminLogin(AuthenticationUtils $authenticationUtils, Request $request): Response
        {
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();
    
            return $this->render('back/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }
    
        #[Route('/login', name: 'app_login')]
        public function userLogin(AuthenticationUtils $authenticationUtils, Request $request, SessionInterface $session): Response
        {
             /** @var UserInterface|null $user */
          $user = $this->getUser();
            if ($user && !$user->getStatus()) {
                $userEmail = $user->getEmail();
                return $this->render('user/disabled_error.html.twig', ['user_email' => $userEmail]);
            }
            
          
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();
    
            return $this->render('Security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }
    
        #[Route(path: '/logout', name: 'app_logout')]
        public function logout(): void
        {
            throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        }
    
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class UserController extends AbstractController
{
    #[Route('/edit', name: 'user_edit')]
    public function editProfile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'Your profile has been updated successfully.');

            return $this->redirectToRoute('app_home1');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/disable', name: 'user_disable')]
    public function disableAccount(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker): Response
    {
       
    /** @var UserInterface|null $user */
    $user = $this->getUser();

    $userEmail = null;

    // Check if the user is authenticated
    if ($authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
        // Store the user's email before invalidating the session
        $userEmail = $user ? $user->getEmail() : null;

        // Invalidate the user's session to force logout
        $tokenStorage->setToken(null);
    }

    // Disable the account
    if ($user) {
        $user->setStatus(false);
        $entityManager->flush();
    }

    // Pass the user's email to the confirmation template
    return $this->render('user/disabled_confirmation.html.twig', [
        'user_email' => $userEmail,
    ]);
    }
    #[Route('/enable', name: 'user_enable')]
    public function enableAccount(Request $request): Response
    {
      
        $email = $request->get('email'); // Assuming you're passing the email in the request query parameters
        
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);
    
        if (!$user) {
        
        }
    
        $user->setStatus(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
    
        // Redirect to the enabled confirmation page
        return $this->render('user/enabled_confirmation.html.twig');
    }
    #[Route('/user/disabled_error', name: 'disabled_error')]
    public function showDisabledError(): Response
    {
        return $this->render('user/disabled_error.html.twig');
    }

}

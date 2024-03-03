<?php

namespace App\Controller\Admin;

use App\Entity\Evenement;
use App\Entity\Partenaire;
use App\Entity\Post;
use App\Entity\Project;
use App\Entity\Societe;
use App\Entity\Sponsor;
use App\Entity\Chat;
use App\Entity\Comment;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Utilisez $this->userRepository pour accéder à votre repository
        $blockedUsersCount = $this->userRepository->countBlockedUsers();
        $activeUsersCount = $this->userRepository->countActiveUsers();

        // Formater les données pour le graphique
        $blockedUsersData = [
            'Comptes bloqués' => $blockedUsersCount,
            'Comptes actifs' => $activeUsersCount
        ];

        // ...

        return $this->render('Bundle\EasyAdminBundle\welcome.html.twig', [
            'blocked_users_data' => json_encode($blockedUsersData)
        ]);
        
    }



    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('JobFlow');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Events', 'fas fa-calendar-alt', Evenement::class);
        yield MenuItem::linkToCrud('Partners', 'fas fa-handshake', Partenaire::class);
        yield MenuItem::linkToCrud('Articles', 'fas fa-newspaper', Post::class);
        yield MenuItem::linkToCrud('Projects', 'fas fa-project-diagram', Project::class);
        yield MenuItem::linkToCrud('Sponsors', 'fas fa-handshake', Sponsor::class);
        yield MenuItem::linkToCrud('Companies', 'fas fa-building', Societe::class);
     //   yield MenuItem::linkToCrud('Chats', 'fas fa-comment', Chat::class);
        yield MenuItem::linkToCrud('Comments', 'fas fa-comment', Comment::class);
    }
}

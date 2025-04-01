<?php

namespace App\Controller\Admin;

use App\Entity\Comments;
use App\Entity\Menus;
use App\Entity\Produits;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Entity\User;
use App\Entity\Reservation;
use App\Entity\Tables;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Restaurant');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Reservations', 'fa fa-calendar', Reservation::class);
        yield MenuItem::linkToCrud('Tables', 'fas fa-map', Tables::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-coffee', Produits::class);
        yield MenuItem::linkToCrud('Menus', 'fas fa-book', Menus::class);
        yield MenuItem::linkToCrud('Comments', 'fas fa-comment', Comments::class);
    }
}

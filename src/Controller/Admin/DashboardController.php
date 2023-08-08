<?php

namespace App\Controller\Admin;

use App\Entity\Boisson;
use App\Entity\Burger;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Complement;
use App\Entity\Frite;
use App\Entity\Livreur;
use App\Entity\Menu;
use Doctrine\ORM\Query\Expr\From;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {

    }
    
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
        ->setController(FriteCrudController::class)
        ->generateUrl();

        return $this->redirect($url);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('BrazilBurger');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::subMenu('Home', 'fa fa-home');

        yield MenuItem::subMenu('Burger', 'fas fa-plus-square')->setSubItems([
            MenuItem::linkToCrud('Create Burger', 'fas fa-plus-square', Burger::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Burger', 'fas fa-eye', Burger::class)
        ]);

        yield MenuItem::subMenu('Frite', 'fas fa-plus-square')->setSubItems([
            MenuItem::linkToCrud('Create Frite', 'fas fa-plus-square', Frite::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Frite', 'fas fa-eye', Frite::class)
        ]);

        yield MenuItem::subMenu('Boisson', 'fas fa-plus-square')->setSubItems([
            MenuItem::linkToCrud('Create Boissons', 'fas fa-plus-square', Boisson::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Boissons', 'fas fa-eye', Boisson::class)
        ]);

        yield MenuItem::subMenu('Complement', 'fas fa-plus-square')->setSubItems([
            MenuItem::linkToCrud('Create Complements', 'fas fa-plus-square', Complement::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Complements', 'fas fa-eye', Complement::class)
        ]);

        yield MenuItem::subMenu('Menu', 'fas fa-plus-square')->setSubItems([
            MenuItem::linkToCrud('Create Menus', 'fas fa-plus-square', Menu::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Menus', 'fas fa-eye', Menu::class)
        ]);

        yield MenuItem::subMenu('Client', 'fas fa-plus-square')->setSubItems([
            MenuItem::linkToCrud('Create Clients', 'fas fa-plus-square', Client::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Clients', 'fas fa-eye', Client::class)
        ]);

        yield MenuItem::subMenu('Livreur', 'fas fa-plus-square')->setSubItems([
            MenuItem::linkToCrud('Create Livreurs', 'fas fa-plus-square', Livreur::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Livreurs', 'fas fa-eye', Livreur::class)
        ]);

        yield MenuItem::subMenu('Commande', 'fas fa-plus-square')->setSubItems([
            MenuItem::linkToCrud('Create Commandes', 'fas fa-plus-square', Commande::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Commandes', 'fas fa-eye', Commande::class)
        ]);
    }
}

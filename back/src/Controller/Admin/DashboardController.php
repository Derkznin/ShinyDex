<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->redirectToRoute('admin_pokemon_index');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Shiny Living Dex - Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Pokémon');
        yield MenuItem::linkTo(PokemonCrudController::class, 'Pokémon', 'fa fa-list');
        yield MenuItem::linkTo(VersionCrudController::class, 'Versions', 'fa fa-images');
        yield MenuItem::linkTo(TagCrudController::class, 'Tags', 'fa fa-tags');
        yield MenuItem::linkTo(TagVersionCrudController::class, 'Tag Versions', 'fa fa-link');

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkTo(UtilisateurCrudController::class, 'Utilisateurs', 'fa fa-users');
        yield MenuItem::linkTo(ObtentionCrudController::class, 'Collections', 'fa fa-star');
        yield MenuItem::linkTo(ObtentionTagCrudController::class, 'Tags d\'obtention', 'fa fa-tag');
        yield MenuItem::linkTo(PokemonFavoriCrudController::class, 'Favoris', 'fa fa-heart');
    }
}

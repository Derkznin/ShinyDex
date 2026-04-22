<?php

namespace App\Controller\Admin;

use App\Entity\PokemonFavori;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class PokemonFavoriCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PokemonFavori::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('utilisateur'),
            AssociationField::new('pokemon'),
            AssociationField::new('version'),
            DateTimeField::new('dateCreation')->hideOnForm(),
            DateTimeField::new('dateModification')->hideOnForm(),
        ];
    }
}

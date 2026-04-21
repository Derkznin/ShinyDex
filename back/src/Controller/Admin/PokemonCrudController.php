<?php

namespace App\Controller\Admin;

use App\Entity\Pokemon;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PokemonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pokemon::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('numero'),
            TextField::new('nom'),
            DateTimeField::new('dateCreation')->hideOnForm(),
            DateTimeField::new('dateModification')->hideOnForm(),
        ];
    }
}

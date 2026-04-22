<?php

namespace App\Controller\Admin;

use App\Entity\Obtention;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class ObtentionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Obtention::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('utilisateur'),
            AssociationField::new('version'),
            AssociationField::new('methodeObtention'),
            DateTimeField::new('dateObtention'),
            IntegerField::new('iterationAvantObtention'),
            TextareaField::new('notes'),
            DateTimeField::new('dateCreation')->hideOnForm(),
            DateTimeField::new('dateModification')->hideOnForm(),
        ];
    }
}

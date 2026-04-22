<?php

namespace App\Controller\Admin;

use App\Entity\ObtentionTag;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class ObtentionTagCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ObtentionTag::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('obtention'),
            AssociationField::new('tag'),
            DateTimeField::new('dateCreation')->hideOnForm(),
            DateTimeField::new('dateModification')->hideOnForm(),
        ];
    }
}

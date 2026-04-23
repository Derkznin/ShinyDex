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
            // `numero` est la clé primaire du Pokémon (1 = Bulbizarre, 25 = Pikachu, etc.)
            // et correspond au numéro officiel du Pokédex National.
            //
            // Contrairement à un ID auto-généré, cette valeur est **saisie manuellement**
            // par l'admin lors de la création : c'est une donnée métier imposée par la licence
            // Pokémon, pas un identifiant technique. Elle doit donc rester visible et éditable
            // sur le formulaire de création.
            //
            // Sur l'écran d'édition, EasyAdmin empêche déjà la modification de la PK côté BDD
            // (Doctrine refuse un UPDATE sur une colonne @Id), mais le champ reste affiché
            // pour référence visuelle dans le formulaire.
            //
            // La contrainte `Assert\Range(min: 1, max: 3000)` sur l'entité (Pokemon.php:37)
            // garantit que la valeur saisie reste cohérente avec la plage Pokédex officielle.
            IntegerField::new('numero'),
            TextField::new('nom'),
            DateTimeField::new('dateCreation')->hideOnForm(),
            DateTimeField::new('dateModification')->hideOnForm(),
        ];
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurCrudController extends AbstractCrudController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // Sur le formulaire de création, le password est obligatoire ;
        // sur le formulaire d'édition, il est optionnel (vide = on garde l'ancien hash).
        $isNew = Crud::PAGE_NEW === $pageName;

        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('username'),
            TextField::new('email'),

            // Q3 — ChoiceField whitelisté : empêche un admin de saisir un rôle libre
            // (ROLE_CHAT, foo, etc.) qui ne correspondrait à aucun access_control Symfony.
            // `allowMultipleChoices()` car `roles` est stocké en JSON (list<string>).
            // `renderExpanded()` affiche des checkboxes plutôt qu'un <select multiple> peu ergonomique.
            ChoiceField::new('roles')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices()
                ->renderExpanded()
                ->setHelp('ROLE_USER est ajouté automatiquement par l\'entité. Cocher ROLE_ADMIN donne l\'accès à /admin.'),

            // Q4 — Password visible en création ET en édition.
            // En édition : champ optionnel, « laisser vide » conserve le hash actuel (cf. updateEntity).
            // Le hashage est géré dans persistEntity / updateEntity — jamais le mot de passe en clair n'atteint la BDD.
            TextField::new('password')
                ->setFormType(PasswordType::class)
                ->setFormTypeOption('required', $isNew)
                ->setFormTypeOption('mapped', true)
                ->setHelp($isNew
                    ? 'Entre 8 et 24 caractères.'
                    : 'Laisser vide pour conserver le mot de passe actuel. Sinon 8 à 24 caractères.'
                )
                ->onlyOnForms(),

            DateTimeField::new('dateCreation')->hideOnForm(),
            DateTimeField::new('dateModification')->hideOnForm(),
        ];
    }

    /**
     * Création d'un utilisateur — le password saisi est toujours présent et doit être hashé.
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Utilisateur && $entityInstance->getPassword()) {
            $hashed = $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword());
            $entityInstance->setPassword($hashed);
        }
        parent::persistEntity($entityManager, $entityInstance);
    }

    /**
     * Édition d'un utilisateur — deux cas :
     *   - Le champ password est laissé vide → on restaure le hash original depuis l'UnitOfWork
     *     (sinon Symfony écraserait le hash avec une chaîne vide).
     *   - Le champ password contient une nouvelle valeur → on la hashe avant persist.
     *
     * Q4 — permet à l'admin de réinitialiser le mot de passe d'un utilisateur sans avoir
     * à passer par une action custom séparée.
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Utilisateur) {
            $submitted = $entityInstance->getPassword();

            if (null === $submitted || '' === $submitted) {
                // Rien saisi : on restaure le hash pré-édition pour ne pas l'écraser.
                // getOriginalEntityData() retourne l'état chargé depuis la BDD avant les modifs du formulaire.
                $originalData = $entityManager->getUnitOfWork()->getOriginalEntityData($entityInstance);
                $entityInstance->setPassword($originalData['password'] ?? '');
            } else {
                // Garde-fou : même règle que RegistrationController (8 à 24 caractères).
                // On refuse silencieusement un mot de passe trop court ou trop long côté admin
                // pour éviter qu'un admin crée un password de 3 caractères.
                $length = strlen($submitted);
                if ($length < 8 || $length > 24) {
                    throw new \InvalidArgumentException('Le mot de passe doit contenir entre 8 et 24 caractères.');
                }

                $hashed = $this->passwordHasher->hashPassword($entityInstance, $submitted);
                $entityInstance->setPassword($hashed);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}

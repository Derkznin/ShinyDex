<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Q2M — DTO d'inscription.
 *
 * On passe par un DTO plutôt qu'un `json_decode()` direct pour 3 raisons :
 *
 *   1. Filtrage des champs côté Serializer : ObjectNormalizer ignore
 *      silencieusement toute clé JSON qui ne correspond pas à une propriété
 *      du DTO. Un attaquant qui POST `{"username":"x","password":"...","roles":["ROLE_ADMIN"]}`
 *      n'arrive JAMAIS à toucher l'entité Utilisateur — `roles` n'existe pas ici,
 *      donc la valeur est tout simplement jetée. Protection contre le mass-assignment.
 *
 *   2. Validation centralisée via contraintes Symfony : `NotBlank`, `Length`, `Regex`, `Email`
 *      remplacent les `empty()`, `strlen()`, checks manuels éparpillés. Les messages
 *      d'erreur sont normalisés et traduisibles.
 *
 *   3. L'entité `Utilisateur` ne valide plus les règles de formulaire (longueur du password brut
 *      par exemple, que l'entité ne peut pas voir puisqu'elle ne stocke que le hash).
 *      Le DTO porte les contraintes d'entrée, l'entité porte les invariants métier.
 *
 * Propriétés `readonly public` : le DTO est immuable une fois hydraté par le Serializer.
 * Symfony instancie via réflexion et affecte directement les champs.
 */
final class RegistrationDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le nom d\'utilisateur est requis.')]
        #[Assert\Length(
            min: 3,
            max: 180,
            minMessage: 'Le nom d\'utilisateur doit faire au moins {{ limit }} caractères.',
            maxMessage: 'Le nom d\'utilisateur ne peut pas dépasser {{ limit }} caractères.',
        )]
        #[Assert\Regex(
            pattern: '/^[a-zA-Z0-9_]+$/',
            message: 'Le nom d\'utilisateur ne peut contenir que lettres, chiffres et underscores.',
        )]
        public readonly ?string $username = null,

        #[Assert\NotBlank(message: 'Le mot de passe est requis.')]
        #[Assert\Length(
            min: 8,
            max: 24,
            minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
            maxMessage: 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.',
        )]
        public readonly ?string $password = null,

        // Email optionnel (aligné sur l'entité `Utilisateur` dont `email` est nullable).
        // Quand il est fourni, on valide qu'il est bien formé.
        #[Assert\Email(message: 'L\'email n\'est pas valide.')]
        #[Assert\Length(max: 255)]
        public readonly ?string $email = null,
    ) {
    }
}

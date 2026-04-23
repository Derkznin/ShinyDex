<?php

namespace App\Controller;

use App\Dto\RegistrationDto;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * Q2M — Inscription via DTO + Serializer + Validator.
     *
     * Pipeline :
     *   1. Vérifier que le client envoie bien du `application/json` (sinon 415).
     *   2. Désérialiser le body dans `RegistrationDto` (sinon 400 si JSON invalide ou types wrongs).
     *   3. Valider le DTO avec les contraintes déclarées dans la classe (sinon 422).
     *   4. Anti-enumeration : on vérifie l'unicité du username SANS exposer s'il est pris ou non
     *      (message volontairement flou).
     *   5. Construire l'entité `Utilisateur`, hasher le password, persister.
     *
     * Ce qu'on gagne par rapport au `json_decode()` direct :
     *   - Plus de `empty($data['...'])` éparpillés : tout part des contraintes sur le DTO.
     *   - Mass-assignment bloqué : si le JSON contient `roles`, `id`, `password_hash`, etc.,
     *     ces clés sont droppées par le Serializer puisque le DTO n'a pas ces propriétés.
     *   - Les erreurs de parsing JSON ne masquent plus rien silencieusement : `NotEncodableValueException`
     *     est catchée explicitement et renvoie un 400 clair.
     */
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        UtilisateurRepository $utilisateurRepository,
    ): JsonResponse {
        // --- 1. Content-Type strict ---------------------------------------
        // On refuse explicitement tout ce qui n'est pas JSON. Évite qu'un client
        // envoie du `application/x-www-form-urlencoded` et se retrouve avec un body
        // mal interprété par le Serializer.
        if (!str_contains((string) $request->headers->get('Content-Type'), 'application/json')) {
            return $this->json(
                ['errors' => ['Content-Type attendu : application/json']],
                Response::HTTP_UNSUPPORTED_MEDIA_TYPE, // 415
            );
        }

        // --- 2. Désérialisation body → DTO --------------------------------
        // `NotEncodableValueException` : JSON malformé (ex. accolade manquante).
        // `UnexpectedValueException` : types incompatibles (ex. `username` envoyé comme int).
        // Dans les deux cas on renvoie 400, sans fuite du détail interne.
        try {
            /** @var RegistrationDto $dto */
            $dto = $serializer->deserialize(
                $request->getContent(),
                RegistrationDto::class,
                'json',
            );
        } catch (NotEncodableValueException|UnexpectedValueException) {
            return $this->json(
                ['errors' => ['JSON invalide']],
                Response::HTTP_BAD_REQUEST, // 400
            );
        }

        // --- 3. Validation des contraintes du DTO -------------------------
        // Le Validator applique `NotBlank`, `Length`, `Regex`, `Email` déclarées dans RegistrationDto.
        // On renvoie 422 (Unprocessable Entity) avec la liste des messages.
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }

            return $this->json(
                ['errors' => $messages],
                Response::HTTP_UNPROCESSABLE_ENTITY, // 422
            );
        }

        // --- 4. Anti-username-enumeration --------------------------------
        // On ne dit JAMAIS « username déjà pris » : un attaquant pourrait lister
        // les comptes existants en testant des usernames. Message générique.
        $existingUser = $utilisateurRepository->findOneBy(['username' => $dto->username]);
        if (null !== $existingUser) {
            return $this->json(
                ['errors' => ['Données invalides']],
                Response::HTTP_UNPROCESSABLE_ENTITY, // 422
            );
        }

        // --- 5. Construction de l'entité ---------------------------------
        // Le DTO porte la saisie brute, l'entité porte l'état persisté.
        // Le password en clair ne sort du DTO que pour passer dans `hashPassword()` —
        // il n'est jamais écrit dans `setPassword()` avant d'être hashé.
        $utilisateur = new Utilisateur();
        $utilisateur->setUsername($dto->username);
        $utilisateur->setEmail($dto->email);
        $utilisateur->setRoles(['ROLE_USER']);
        $utilisateur->setPassword(
            $passwordHasher->hashPassword($utilisateur, $dto->password),
        );

        // Dernier filet : on valide aussi l'entité (UniqueEntity email, etc.)
        // Utile si l'email fourni est déjà utilisé : `UniqueEntity` s'en charge,
        // pas le DTO (qui ne parle pas à la BDD).
        $entityErrors = $validator->validate($utilisateur);
        if (count($entityErrors) > 0) {
            $messages = [];
            foreach ($entityErrors as $error) {
                $messages[] = $error->getMessage();
            }

            return $this->json(
                ['errors' => $messages],
                Response::HTTP_UNPROCESSABLE_ENTITY, // 422
            );
        }

        $entityManager->persist($utilisateur);
        $entityManager->flush();

        return $this->json(
            [
                'message' => 'Utilisateur créé avec succès',
                'username' => $utilisateur->getUsername(),
            ],
            Response::HTTP_CREATED, // 201
        );
    }
}

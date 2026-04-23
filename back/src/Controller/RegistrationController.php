<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        UtilisateurRepository $utilisateurRepository,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (empty($data['username']) || empty($data['password'])) {
            return $this->json(['message' => 'Username et password requis'], 400);
        }

        // Validation du password brut AVANT tout hashage
        // Le hash d'une chaîne vide ou triviale réussit côté Symfony — on valide ici en amont
        $password = (string) $data['password'];
        if (strlen($password) < 8 || strlen($password) > 24) {
            return $this->json(['errors' => ['Le password doit contenir entre 8 et 24 caractères']], 422);
        }

        $existingUser = $utilisateurRepository->findOneBy(['username' => $data['username']]);
        if ($existingUser) {
            // Message volontairement générique : évite l'username enumeration
            return $this->json(['errors' => ['Données invalides']], 422);
        }

        $utilisateur = new Utilisateur();
        $utilisateur->setUsername($data['username']);
        $utilisateur->setEmail($data['email'] ?? null);
        $utilisateur->setRoles(['ROLE_USER']);

        $errors = $validator->validate($utilisateur);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }

            return $this->json(['errors' => $messages], 422);
        }

        $hashedPassword = $passwordHasher->hashPassword($utilisateur, $password);
        $utilisateur->setPassword($hashedPassword);

        $entityManager->persist($utilisateur);
        $entityManager->flush();

        return $this->json([
            'message' => 'Utilisateur créé avec succès',
            'username' => $utilisateur->getUsername(),
        ], 201);
    }
}
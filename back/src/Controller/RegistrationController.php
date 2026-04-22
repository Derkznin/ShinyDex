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

        $existingUser = $utilisateurRepository->findOneBy(['username' => $data['username']]);
        if ($existingUser) {
            return $this->json(['message' => 'Ce username est déjà pris'], 409);
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

        $hashedPassword = $passwordHasher->hashPassword($utilisateur, $data['password']);
        $utilisateur->setPassword($hashedPassword);

        $entityManager->persist($utilisateur);
        $entityManager->flush();

        return $this->json([
            'message' => 'Utilisateur créé avec succès',
            'username' => $utilisateur->getUsername(),
        ], 201);
    }
}

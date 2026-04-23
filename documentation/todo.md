# Bazardo
## PRIO 1 création de compte, authentification, déconnexion, changement de mot de pass sur symfonfy et api plateform avec bruno
## Espace admin vuejs - batch import 
## Layout (navbar etc)
## View principal
## Liste des pokemon, card mère par pokemon et cards détails des versions et obtention par pokemon
## Filtre et barre de recherche
## Espace indicateurs


# Back
### 1. JWT Setup
- [x] Installer LexikJWTAuthenticationBundle (`composer require lexik/jwt-authentication-bundle`)
- [x] Générer les clés JWT (`php bin/console lexik:jwt:generate-keypair`)
- [x] Configurer `config/packages/lexik_jwt_authentication.yaml`
- [x] Configurer les variables d'env `JWT_SECRET_KEY`, `JWT_PUBLIC_KEY`, `JWT_PASSPHRASE`

### 2. Security.yaml
- [x] Configurer le firewall `login` (json_login sur `/api/login`)
- [x] Configurer le firewall `api` (stateless + jwt)
- [x] Configurer le firewall `admin` (EasyAdmin protégé par ROLE_ADMIN)
- [x] Configurer `access_control` pour les routes publiques/protégées

### 3. Inscription / Authentification
- [x] Créer `RegistrationController` avec route `POST /api/register`
- [x] Validation des données (email, username, password)
- [x] Hashage du mot de passe
- [x] Création de l'utilisateur
- [x] Retourner le JWT directement après inscription
- [x] Tester `/api/login` avec Bruno (retourne un JWT)
- [x] Tester `/api/register` avec Bruno

### 4. Annotations ApiResource avec sécurité
- [x] **Pokemon** — GET public, POST/PATCH/DELETE ROLE_ADMIN
- [x] **Tag** — GET public, POST/PATCH/DELETE ROLE_ADMIN
- [x] **Version** — GET public, POST/PATCH/DELETE ROLE_ADMIN
- [x] **TagVersion** — GET public, POST/PATCH/DELETE ROLE_ADMIN
- [x] **Obtention** — toutes opérations ROLE_USER, filtrage par utilisateur connecté
- [x] **ObtentionTag** — toutes opérations ROLE_USER, filtrage par utilisateur connecté
- [x] **PokemonFavori** — toutes opérations ROLE_USER, filtrage par utilisateur connecté

### 5. Isolation des données utilisateur
- [x] Créer une extension Doctrine `CurrentUserExtension` pour filtrer automatiquement les `Obtention`, `ObtentionTag` et `PokemonFavori` par utilisateur connecté

### 6. CORS
- [ ] Installer NelmioCorsBundle (`composer require nelmio/cors-bundle`)
- [ ] Configurer `config/packages/nelmio_cors.yaml` pour autoriser `localhost:5173`

### 7. Protection EasyAdmin
- [x] Ajouter `#[IsGranted('ROLE_ADMIN')]` sur `DashboardController`

### 8. Validation des données côté symfony
- [x] utiliser des validateurs dans les entités ou les assert

### 9. Sécurité RegistrationController
- [x] validation de données

### 8. Tests Bruno
- [x] POST `/api/register` → créer un compte
- [x] POST `/api/login` → récupérer un JWT
- [ ] GET `/api/pokemon` sans JWT → 200 (public)
- [ ] POST `/api/pokemon` sans JWT → 401
- [ ] POST `/api/pokemon` avec JWT ROLE_ADMIN → 201
- [x] GET `/api/obtentions` avec JWT ROLE_USER → uniquement ses données
- [ ] GET `/api/obtentions` avec JWT d'un autre user → aucune donnée

-----------------------------------------------------------------------------------------------

# Front
## Auth — Backlog MVP
### Déconnexion ✅
- [x] Store auth — supprimer le token + reset état
- [x] Bouton déconnexion dans la navbar
- [x] Redirection vers `/connexion`

### Création de compte
- [ ] Route `/inscription`
- [ ] Vue `InscriptionView.vue`
- [ ] Composant `InscriptionForm.vue`
- [ ] Service `auth.service.ts` — appel POST `/api/register`
- [ ] Redirection vers `/connexion` après succès

### Connexion
- [ ] Composant `ConnexionForm.vue`
- [ ] Service `auth.service.ts` — appel POST `/api/login`
- [ ] Store auth — stocker le token + `isAuthenticated = true`
- [ ] Redirection vers `/` après succès

### Reset mot de passe par mail
- [ ] Route `/mot-de-passe-oublie`
- [ ] Vue + Composant — formulaire saisie email
- [ ] Service — appel POST `/api/reset-password`
- [ ] Vue + Composant — formulaire nouveau mot de passe (lien reçu par mail)
- [ ] Service — appel POST `/api/reset-password/confirm`

-----------------------------------------------------------------------------------------------

# DevOps — Shiny Living Dex Tracker

## ✅ Prérequis
- [x] Repo public GitHub existant
- [x] Docker Compose fonctionnel en local (3 services)
- [x] Dockerfiles backend + frontend opérationnels
- [x] Fichier `.env.example` à la racine

---

## 1. Préparation du repo
### 1.1 Environnements Git
- [ ] Définir la stratégie de branches (`dev` → `main`)
- [ ] Protéger la branche `main` (no push direct, PR obligatoire)
- [ ] Protéger la branche `dev` (PR obligatoire)
### 1.2 Secrets GitHub
- [ ] Ajouter les secrets nécessaires dans GitHub → Settings → Secrets
  - Credentials Scaleway (SSH)
  - Variables d'env de prod (JWT, DB, CORS...)
### 1.3 Fichiers de config CI à créer
- [ ] `.github/workflows/ci.yml` — pipeline PR
- [ ] `.github/workflows/deploy.yml` — pipeline déploiement

---

## 2. Serveur Scaleway
### 2.1 Création du VPS
- [ ] Créer un compte Scaleway
- [ ] Provisionner une instance (Ubuntu 24, specs adaptées)
- [ ] Configurer l'accès SSH + clé publique### 2.2 Installation Coolify
- [ ] Installer Coolify via le script officiel
- [ ] Accéder au dashboard Coolify
- [ ] Configurer le domaine + certificat SSL automatique
### 2.3 Connexion GitHub ↔ Coolify
- [ ] Connecter le repo GitHub à Coolify
- [ ] Configurer le projet Docker Compose dans Coolify
- [ ] Renseigner toutes les variables d'env de prod dans Coolify
### 2.4 Premier déploiement manuel
- [ ] Déclencher un déploiement depuis Coolify
- [ ] Vérifier que les 3 services démarrent correctement
- [ ] Vérifier l'accès public (frontend + API)
- [ ] Exécuter les migrations Doctrine en prod

---

## 3. Pipeline PR → `dev`
### 3.1 Analyse statique PHP
- [ ] Configurer PHPStan (niveau à définir)
- [ ] Intégrer dans la GitHub Action
### 3.2 Style de code
- [ ] Configurer PHP CS Fixer
- [ ] Intégrer dans la GitHub Action
### 3.3 Tests unitaires
- [ ] Configurer PHPUnit (Symfony)
- [ ] Écrire les premiers tests (à minima sur les entités critiques)
- [ ] Intégrer dans la GitHub Action
### 3.4 Tests API Playwright
- [ ] Finaliser la liste des cas de test Bruno → Playwright
- [ ] Configurer l'environnement Playwright dans la GitHub Action
- [ ] Intégrer dans la GitHub Action
### 3.5 Règles de validation PR
- [ ] La PR ne peut merger que si tous les checks passent
- [ ] Revue de code obligatoire (optionnel pour projet solo)

---

## 4. Pipeline déploiement → `main`
### 4.1 Déploiement automatique
- [ ] Configurer le webhook Coolify → GitHub
- [ ] Vérifier le déclenchement automatique sur push `main`
- [ ] Vérifier le redémarrage propre des services
### 4.2 Post-déploiement
- [ ] Exécuter les migrations automatiquement après déploiement
- [ ] Vider le cache Symfony en prod

---

## 5. Pipeline tests programmés (optionnel)
### 5.1 Tests API planifiés
- [ ] Configurer une GitHub Action déclenchée manuellement (`workflow_dispatch`)
- [ ] Ajouter un déclenchement planifié (ex: chaque nuit)
- [ ] Rapport des résultats (email ou notification)

---

## 6. Monitoring

- [ ] Activer les alertes Coolify (CPU, RAM, disk)
- [ ] Configurer un canal de notification (email ou Discord)
- [ ] Vérifier la politique de backup PostgreSQL

### 6.1 Backups PostgreSQL
- [ ] Configurer les backups automatiques dans Coolify (snapshots BDD)
- [ ] Définir la rétention (ex: 7 jours glissants)
- [ ] Tester la procédure de restauration au moins une fois
- [ ] Vérifier que les backups sont stockés hors du VPS (S3 Scaleway Object Storage)


# Tests
## 1. Tests unitaires PHP (PHPUnit — `TestCase`)
### 1.1 `TimestampableTrait`
- [ ] Créer `tests/Unit/Entity/Trait/TimestampableTraitTest.php`
  - [ ] Tester `setDateCreationValue()` — initialise `dateCreation` et `dateModification`
  - [ ] Tester `setDateModificationValue()` — modifie uniquement `dateModification`
### 1.2 Entités
- [ ] Créer `tests/Unit/Entity/UtilisateurTest.php`
  - [ ] `getRoles()` retourne toujours au minimum `ROLE_USER`
  - [ ] `getRoles()` déduplique les rôles
  - [ ] `getUserIdentifier()` retourne le `username`
- [ ] Créer `tests/Unit/Entity/PokemonTest.php`
  - [ ] `addVersion()` configure la relation inverse et ne duplique pas
  - [ ] `removeVersion()` met à null la relation inverse
- [ ] Créer `tests/Unit/Entity/ObtentionTest.php`
  - [ ] `addObtentionTag()` configure la relation inverse et ne duplique pas
  - [ ] `removeObtentionTag()` met à null la relation inverse
### 1.3 `ObtentionProcessor`
- [ ] Créer `tests/Unit/State/ObtentionProcessorTest.php`
  - [ ] `$data` implémente `UserOwnedInterface` → `setUtilisateur()` appelé avec le user connecté
  - [ ] `$data` n'implémente pas `UserOwnedInterface` → `setUtilisateur()` non appelé
  - [ ] `persistProcessor->process()` toujours appelé et son résultat retourné
---
 
## 3. Tests API (PHPUnit — `ApiTestCase` API Platform)
### 3.1 Auth
- [ ] Créer `tests/Api/AuthTest.php`
  - [ ] `POST /api/register` données valides → 201
  - [ ] `POST /api/register` username existant → 409
  - [ ] `POST /api/register` sans username/password → 400
  - [ ] `POST /api/register` username < 3 caractères → 422
  - [ ] `POST /api/register` username avec caractères spéciaux → 422
  - [ ] `POST /api/register` email invalide → 422
  - [ ] `POST /api/login` credentials valides → 200 + token JWT présent dans la réponse
  - [ ] `POST /api/login` mauvais password → 401
  - [ ] `POST /api/login` username inexistant → 401
### 3.2 Routes publiques
- [ ] Créer `tests/Api/PublicRoutesTest.php`
  - [ ] `GET /api/pokemons` sans JWT → 200
  - [ ] `GET /api/pokemons/{id}` sans JWT → 200
  - [ ] `GET /api/tags` sans JWT → 200
  - [ ] `GET /api/versions` sans JWT → 200
  - [ ] `GET /api/tag_versions` sans JWT → 200
  - [ ] `POST /api/pokemons` sans JWT → 401
  - [ ] `POST /api/tags` sans JWT → 401
  - [ ] `POST /api/versions` sans JWT → 401
  - [ ] `POST /api/tag_versions` sans JWT → 401
  - [ ] `PATCH /api/pokemons/{id}` sans JWT → 401
  - [ ] `DELETE /api/pokemons/{id}` sans JWT → 401
### 3.3 Restrictions user
- [ ] Créer `tests/Api/UserRestrictionsTest.php`
  - [ ] `POST /api/pokemons` JWT `ROLE_USER` → 403
  - [ ] `PATCH /api/pokemons/{id}` JWT `ROLE_USER` → 403
  - [ ] `DELETE /api/pokemons/{id}` JWT `ROLE_USER` → 403
  - [ ] `POST /api/tags` JWT `ROLE_USER` → 403
  - [ ] `PATCH /api/tags/{id}` JWT `ROLE_USER` → 403
  - [ ] `DELETE /api/tags/{id}` JWT `ROLE_USER` → 403
  - [ ] `POST /api/versions` JWT `ROLE_USER` → 403
  - [ ] `PATCH /api/versions/{id}` JWT `ROLE_USER` → 403
  - [ ] `DELETE /api/versions/{id}` JWT `ROLE_USER` → 403
  - [ ] `POST /api/tag_versions` JWT `ROLE_USER` → 403
  - [ ] `DELETE /api/tag_versions/{id}` JWT `ROLE_USER` → 403
### 3.4 Isolation des données — `Obtention`
- [ ] Créer `tests/Api/ObtentionTest.php`
  - [ ] `GET /api/obtentions` sans JWT → 401
  - [ ] `GET /api/obtentions` JWT `user_A` → 200 + uniquement ses obtentions
  - [ ] `GET /api/obtentions` JWT `user_B` → 200 + liste vide
  - [ ] `POST /api/obtentions` JWT `ROLE_USER` → 201 + `utilisateur` auto-setté depuis le JWT
  - [ ] `POST /api/obtentions` dateObtention dans le futur → 422
  - [ ] `POST /api/obtentions` iterationAvantObtention > 100000 → 422
  - [ ] `POST /api/obtentions` notes > 5000 caractères → 422
  - [ ] `GET /api/obtentions/{id}` JWT propriétaire → 200
  - [ ] `GET /api/obtentions/{id}` JWT autre user → 404
  - [ ] `PATCH /api/obtentions/{id}` JWT autre user → 404
  - [ ] `DELETE /api/obtentions/{id}` JWT propriétaire → 204
  - [ ] `DELETE /api/obtentions/{id}` JWT autre user → 404
### 3.5 Isolation des données — `ObtentionTag`
- [ ] Créer `tests/Api/ObtentionTagTest.php`
  - [ ] `GET /api/obtention_tags` sans JWT → 401
  - [ ] `GET /api/obtention_tags` JWT `user_A` → 200 + uniquement ses tags
  - [ ] `GET /api/obtention_tags` JWT `user_B` → 200 + liste vide
  - [ ] `GET /api/obtention_tags/{id}` JWT autre user → 404
### 3.6 Isolation des données — `PokemonFavori`
- [ ] Créer `tests/Api/PokemonFavoriTest.php`
  - [ ] `GET /api/pokemon_favoris` sans JWT → 401
  - [ ] `GET /api/pokemon_favoris` JWT `user_A` → 200 + uniquement ses favoris
  - [ ] `GET /api/pokemon_favoris` JWT `user_B` → 200 + liste vide
  - [ ] `POST /api/pokemon_favoris` JWT `ROLE_USER` → 201 + `utilisateur` auto-setté depuis le JWT
  - [ ] `POST /api/pokemon_favoris` doublon (même utilisateur + même pokemon) → 422
  - [ ] `GET /api/pokemon_favoris/{id}` JWT autre user → 404
  - [ ] `DELETE /api/pokemon_favoris/{id}` JWT propriétaire → 204
  - [ ] `DELETE /api/pokemon_favoris/{id}` JWT autre user → 404

---
 
## 4. Tests composants Vue (Vitest + Vue Test Utils)
 
> Appels HTTP mockés. Pas de backend requis.
 
### 4.1 Store `auth.store.ts`
- [ ] Créer `tests/unit/stores/auth.store.test.ts`
  - [ ] État initial : `isAuthenticated = false`, `token = null`
  - [ ] `login()` succès → `isAuthenticated = true`, token en sessionStorage
  - [ ] `logout()` → `isAuthenticated = false`, sessionStorage vidé
  - [ ] Rechargement avec token existant en sessionStorage → `isAuthenticated = true`
### 4.2 Router guard
- [ ] Créer `tests/unit/router/guard.test.ts`
  - [ ] Route `requiresAuth` sans token → redirection `/connexion`
  - [ ] Route `requiresAuth` avec token → accès autorisé
  - [ ] Route publique sans token → accès autorisé
### 4.3 Composant `NavBar.vue`
- [ ] Créer `tests/unit/components/NavBar.test.ts`
  - [ ] Bouton "Déconnexion" visible si `isAuthenticated = true`
  - [ ] Bouton "Déconnexion" absent si `isAuthenticated = false`
  - [ ] Clic "Déconnexion" → appelle `logout()`
### 4.4 Composant `ConnexionForm.vue`
- [ ] Créer `tests/unit/components/ConnexionForm.test.ts`
  - [ ] Soumission champs vides → message d'erreur affiché
  - [ ] Soumission valide → appelle le service auth
  - [ ] Réponse 401 → message d'erreur affiché
### 4.5 Composant `InscriptionForm.vue`
- [ ] Créer `tests/unit/components/InscriptionForm.test.ts`
  - [ ] Soumission valide → appelle `POST /api/register`
  - [ ] Réponse 409 → message d'erreur "username déjà pris"
  - [ ] Réponse 422 → affichage des erreurs de validation
---
 
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
  - Validation des données (email, username, password)
  - Hashage du mot de passe
  - Création de l'utilisateur
  - Retourner le JWT directement après inscription
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
- [ ] Ajouter `#[IsGranted('ROLE_ADMIN')]` sur `DashboardController`

### 8. Validation des données côté symfony
- [x] utiliser des validateurs


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


# Suite
## PRIO 1 Mise en place de l'api apiplateforme
## PRIO 1 création de compte, authentification, déconnexion, changement de mot de pass sur symfonfy et api plateform avec bruno
## Espace admin vuejs - batch import 
## Layout (navbar etc)
## View principal
## Liste des pokemon, card mère par pokemon et cards détails des versions et obtention par pokemon
## Filtre et barre de recherche
## Espace indicateurs
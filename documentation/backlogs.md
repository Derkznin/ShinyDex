21/04/2026

# ROADMAP - SHINY LIVING DEX TRACKER

## Étapes de développement

### ✅ Infrastructure
1. **Setup Docker Compose** (backend + frontend + db)
2. **Configuration variables d'environnement** (.env backend/frontend, intégration Docker)
3. **Création entités Doctrine + TimestampableTrait**
   - 7 entités : Pokemon, Version, Tag, TagVersion, Utilisateur, Obtention, ObtentionTag
   - Relations bidirectionnelles configurées
   - TimestampableTrait avec PrePersist/PreUpdate
4. **Migrations Doctrine** — schéma BDD généré et exécuté
5. **Mise en place EasyAdmin Bundle** — CRUDs pour toutes les entités

### 🚧 Backend *(en cours)*
6. **Configuration API Platform + CORS**
7. **Configuration upload/serving images statiques** (Symfony public/uploads + headers CORS)
8. **Système authentification JWT + reset mot de passe par mail**

### 🚧 Frontend *(en cours)*
9. **Infrastructure Vue 3** ✅
   - Structure dossiers, Vite + TypeScript, Vuetify
   - Router + guards d'authentification
   - Store Pinia auth
   - Service Axios + intercepteurs JWT
   - NavBar conditionnelle
10. **Authentification utilisateur** 🚧
    - Inscription
    - Connexion
    - Déconnexion
    - Reset mot de passe par mail
11. **Pokédex shiny**
    - Grille de cartes avec système anti-spoil
    - Filtres (génération, région, statut, nom)
    - Marquer possédé / retirer
    - Statistiques personnelles
12. **Profil utilisateur**
    - Tableau de bord personnel
    - Statistiques par génération / région

### 📋 Backlog
13. **Import en masse (admin)**
    - Intégration PokéAPI
    - Sélection multiple + création batch Pokemon/Version/Tags
14. **Déploiement**

---

## Variables d'environnement

> ⚠️ Ne jamais committer de vraies valeurs. Les fichiers `.env` sont dans `.gitignore`.
> Utiliser les fichiers `.env.example` fournis comme référence.

### Docker Compose :Injectées via `environment:` dans `docker-compose.yml` à la racine du projet

#### Base de données
- `POSTGRES_DB`, `POSTGRES_USER`, `POSTGRES_PASSWORD`

#### Backend
- `DATABASE_URL` — connexion PostgreSQL via Docker
- `JWT_SECRET_KEY`, `JWT_PUBLIC_KEY`, `JWT_PASSPHRASE` — clés JWT (à générer localement)
- `CORS_ALLOW_ORIGIN` — origine autorisée pour le frontend

#### Frontend
- `VITE_API_BASE_URL` — URL de l'API backend


11/04/2026

# ROADMAP - SHINY LIVING DEX TRACKER

## Étapes de développement

1. Setup Docker Compose (backend + frontend + db)

2. Configuration variables d'environnement (.env backend/frontend, intégration Docker)

3. Création entités Doctrine + TimestampableTrait

4. Migrations Doctrine

5. Mise en place EasyAdmin Bundle

6. Configuration API Platform + CORS

7. Configuration upload/serving images statiques (Symfony public/uploads + headers CORS)

8. Système authentification JWT

9. Composants Vue 3 de base + Axios

# ✨ Shiny Living Dex Tracker

Application web pour suivre sa collection de Pokémon chromatiques (shinies).
Gérez votre Shiny Living Dex complet — toutes les formes, toutes les variantes.

---

## Stack technique

**Backend**
- Symfony 7 + FrankenPHP (port 8000)
- API Platform (REST + Swagger UI)
- Doctrine ORM + PostgreSQL
- Authentification JWT

**Frontend**
- Vue 3 + TypeScript + Vite (port 5173)
- Vuetify 3 (UI)
- Pinia (state management)
- Axios (HTTP)

**Infrastructure**
- Docker Compose (3 services : backend, frontend, database)

---

## Prérequis

- Docker Desktop
- Git

---

## Installation

```bash
git clone https://github.com/xxx/shiny-dex.git
cd shiny-dex
cp .env.example .env  # remplir les variables
docker compose up --build
```

**Accès :**
- Frontend : http://localhost:5173
- API : http://localhost:8000/api
- Swagger : http://localhost:8000/api/docs
- Admin : http://localhost:8000/admin


## Modèle de données

| Table | Description |
|---|---|
| `pokemon` | Pokémon de base |
| `version` | Formes shinies ou alternatives d'un Pokémon |
| `tag` | Catégories (génération, région, forme, jeux, méthode d'obtention...) |
| `tag_version` | Association tags ↔ versions |
| `utilisateur` | Comptes utilisateurs |
| `obtention` | Collection personnelle de chaque utilisateur |
| `obtention_tag` | Tags pour chaque obtention de chaque utilisateur |

---

## Fonctionnalités prévues

- [ ] Authentification (inscription, connexion, reset mot de passe)
- [ ] Pokédex shiny avec système anti-spoil
- [ ] Filtres par génération, région, statut
- [ ] Statistiques personnelles
- [ ] Import en masse depuis PokéAPI (admin)
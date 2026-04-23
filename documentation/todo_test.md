# Audit API — Shiny Living Dex
## Routes publiques (sans JWT)
### Pokémon
- [ ] GET `/api/pokemons` → 200 + liste
- [ ] GET `/api/pokemons/{id}` → 200 + objet
- [ ] POST `/api/pokemons` sans JWT → 401
- [ ] PATCH `/api/pokemons/{id}` sans JWT → 401
- [ ] DELETE `/api/pokemons/{id}` sans JWT → 401
### Tag
- [ ] GET `/api/tags` → 200 + liste
- [ ] GET `/api/tags/{id}` → 200 + objet
- [ ] POST `/api/tags` sans JWT → 401
- [ ] PATCH `/api/tags/{id}` sans JWT → 401
- [ ] DELETE `/api/tags/{id}` sans JWT → 401
### Version
- [ ] GET `/api/versions` → 200 + liste
- [ ] GET `/api/versions/{id}` → 200 + objet
- [ ] POST `/api/versions` sans JWT → 401
- [ ] PATCH `/api/versions/{id}` sans JWT → 401
- [ ] DELETE `/api/versions/{id}` sans JWT → 401
### TagVersion
- [ ] GET `/api/tag_versions` → 200 + liste
- [ ] GET `/api/tag_versions/{id}` → 200 + objet
- [ ] POST `/api/tag_versions` sans JWT → 401
- [ ] PATCH `/api/tag_versions/{id}` sans JWT → 401
- [ ] DELETE `/api/tag_versions/{id}` sans JWT → 401
---
## Auth
- [ ] POST `/api/register` → 201 + user créé
- [ ] POST `/api/register` username existant → 409
- [ ] POST `/api/register` données invalides → 422
- [ ] POST `/api/register` sans username/password → 400
- [ ] POST `/api/login` credentials valides → 200 + JWT
- [ ] POST `/api/login` mauvais password → 401
- [ ] POST `/api/login` username inexistant → 401
---
## Routes ROLE_ADMIN (avec JWT admin)
### Pokémon
- [ ] POST `/api/pokemons` JWT ROLE_USER → 403
- [ ] POST `/api/pokemons` JWT ROLE_ADMIN → 201
- [ ] PATCH `/api/pokemons/{id}` JWT ROLE_ADMIN → 200
- [ ] DELETE `/api/pokemons/{id}` JWT ROLE_ADMIN → 204
### Tag / Version / TagVersion
- [ ] POST JWT ROLE_USER → 403
- [ ] POST JWT ROLE_ADMIN → 201
- [ ] PATCH JWT ROLE_ADMIN → 200
- [ ] DELETE JWT ROLE_ADMIN → 204
---
## Routes ROLE_USER — Isolation des données
### Obtention
- [ ] GET `/api/obtentions` sans JWT → 401
- [ ] GET `/api/obtentions` JWT ROLE_USER → 200 + uniquement ses données
- [ ] GET `/api/obtentions` JWT user B → 200 + aucune donnée du user A
- [ ] POST `/api/obtentions` JWT ROLE_USER → 201 + utilisateur auto-setté
- [ ] POST `/api/obtentions` sans champ utilisateur → utilisateur pris du JWT
- [ ] GET `/api/obtentions/{id}` JWT autre user → 404 ou 403
- [ ] PATCH `/api/obtentions/{id}` JWT autre user → 404 ou 403
- [ ] DELETE `/api/obtentions/{id}` JWT autre user → 404 ou 403
### ObtentionTag
- [ ] GET `/api/obtention_tags` sans JWT → 401
- [ ] GET `/api/obtention_tags` JWT ROLE_USER → uniquement ses données
- [ ] GET `/api/obtention_tags/{id}` JWT autre user → 404 ou 403
### PokemonFavori
- [ ] GET `/api/pokemon_favoris` sans JWT → 401
- [ ] GET `/api/pokemon_favoris` JWT ROLE_USER → uniquement ses données
- [ ] POST `/api/pokemon_favoris` JWT ROLE_USER → 201 + utilisateur auto-setté
- [ ] POST `/api/pokemon_favoris` doublon → 422 (contrainte unique)
- [ ] GET `/api/pokemon_favoris/{id}` JWT autre user → 404 ou 403
---
## Validation des données
### Pokémon
- [ ] POST numero < 1 → 422
- [ ] POST numero > 3000 → 422
- [ ] POST nom vide → 422
### Obtention
- [ ] POST dateObtention dans le futur → 422
- [ ] POST iterationAvantObtention > 100000 → 422
- [ ] POST notes > 5000 caractères → 422
### Utilisateur (register)
- [ ] username < 3 caractères → 422
- [ ] username avec caractères spéciaux → 422
- [ ] email invalide → 422
---
## Sécurité EasyAdmin
- [ ] GET `/admin` sans session → redirect `/admin/login`
- [ ] GET `/admin` session ROLE_USER → 403
- [ ] GET `/admin` session ROLE_ADMIN → 200
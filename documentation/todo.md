# Back
## Modifications Doctrine à faire
### Renommage UtilisateurVersion → Obtention
1. Renommer `UtilisateurVersion` → `Obtention` (entité + repository)
2. Supprimer la contrainte unique `uniq_utilisateur_version`
3. Mettre à jour les relations dans `Utilisateur` et `Version`
4. Mettre à jour `UtilisateurVersionCrudController` → `ObtentionCrudController`

### Nouvelle entité ObtentionTag
5. Créer l'entité `ObtentionTag` (avec TimestampableTrait)
   - ManyToOne vers `Obtention`
   - ManyToOne vers `Tag`
6. Créer `ObtentionTagRepository`
7. Ajouter CRUD EasyAdmin `ObtentionTagCrudController`

### Nouvelle entité PokemonFavori
8. Créer l'entité `PokemonFavori` (avec TimestampableTrait)
   - ManyToOne vers `Utilisateur`
   - ManyToOne vers `Pokemon`
   - ManyToOne vers `Version`
   - Contrainte unique `(id_utilisateur, id_pokemon)`
9. Créer `PokemonFavoriRepository`
10. Ajouter CRUD EasyAdmin `PokemonFavoriCrudController`

### Migrations
11. Générer la migration (`make:migration`)
    - `ALTER TABLE utilisateur_version RENAME TO obtention`
    - `DROP CONSTRAINT uniq_utilisateur_version`
    - `CREATE TABLE obtention_tag`
    - `CREATE TABLE pokemon_favori`
12. Exécuter la migration (`doctrine:migrations:migrate`)

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
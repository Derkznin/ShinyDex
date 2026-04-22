21/04/2026

- maquette des cards pokemon - ok
- maquette de la page - ko
- maquette du layout (navbar logo etc) - ko 

## Fonctionnalités principales
### 1. Gestion utilisateur
- Inscription / Connexion (JWT stocké en sessionStorage)
- Profil personnel avec statistiques
- Multi-utilisateurs : chaque utilisateur a sa propre collection isolée
- Rôles : ROLE_USER (standard), ROLE_ADMIN (gestion des données Pokémon)

### 2. Pokédex Shiny
- Liste complète de tous les Pokémon avec leurs versions
- Carte par pokemon  : image, numéro, nom, tags, statut possédé/non possédé, nombre de versions possédé
- Détails d'une card pokemon : carouselle avec toutes les versions et les obtentions par versions, peut aussi définir l'image par defaut de la card pokemon
- Système recto/verso : recto grisé (anti-spoil), verso révélé à la demande
- Card pokemon grisée quand aucune version obtenu
- System de favoris, l'utilisateur pour selectionner une version favorite par pokemon dans le détails des version d'un pokemon, pour gérer l'image de la card mere du pokemon.
- Grille responsive (4 cartes/ligne desktop)
- Barre de recherche (numéro, noms, tags)
- filtres (Catégories de tags, tags, obtentions)
- Avoir un espace pour pouvoir créer des indicateurs à la volée par utilisateur : total capturés (74/200), manquants (int), % obtenu, croisé par tag (tags de génération, de version (shiny, alternatifs etc))

### 4. Import batch (interface admin)
- Interface admin Vue à `/admin/import-batch`
- Récupération des Pokémon depuis PokéAPI
- Sélection multiple + création et association en batch de Pokemon/Version/Tags


09/04/2026

Description : 
Pouvoir rassembler dans une application web les pokemon chromatique capturés sur les jeux Pokemon

Ecrans:
- 


1 - Formes shiny
    Liste de tous les pokemon chromatiques
    Permettre de savoir si le shiny est possedé
    Statistiques : Total capturé, manquant, % obtenu, Shiny trouvés par génération
    Système de recherche
    Système de trie
    Photo du pokémon
    Système de card avec 4 pokemon par ligne
    Pour chaque card : image, numéro, nom, génération, possedé ou non
    Système anti spoil pour chaque card, grisé par défaut
    Idée de card : recto version classique disabled (grisé), verso version chromatique

2 - Formes régionales
    Un pokémon peut avoir une ou plusieurs formes régionales

3 - Formes alternatives
    Pouvoir lister les pokémon que l'on souhaite rajouter dans le shiny living dex
    Ex: formes males ou femelles, autres formes, couleurs etc...

Avoir une fiche résumé par pokémon qui indique la version mâle et femelle et ces autres formes s'il y en a
Pour chaque version du pokémon, avoir un système d'ajout pour le shiny ling dex formes alternatives 


Contexte du projet
Je développe une application web pour suivre ma collection de Pokémon chromatiques (shinies), dans le cadre d'un "Shiny Living Dex" (posséder un exemplaire shiny de chaque Pokémon et de ses formes).
Fonctionnalités principales
Pokédex Shiny (formes de base)
Liste complète de tous les Pokémon avec leur version chromatique. Pour chaque Pokémon, une carte affichant : image, numéro, nom, génération, et statut possédé/non possédé. Les cartes sont grisées par défaut (anti-spoil) et se révèlent à la demande. Idée de carte recto/verso : recto = version classique désactivée (grisée), verso = version chromatique. Affichage en grille de 4 cartes par ligne. Système de recherche et de tri. Statistiques globales : total capturé, manquants, % obtenu, shinies par génération.
Formes régionales
Gestion des variantes régionales d'un Pokémon (ex : Alola, Galar, Hisui…). Un Pokémon peut avoir une ou plusieurs formes régionales, chacune trackée indépendamment.
Formes alternatives (Shiny Living Dex étendu)
Gestion des formes supplémentaires que je souhaite inclure dans mon dex : différences mâle/femelle, formes alternatives, variantes de couleur, etc. Chaque Pokémon concerné possède une fiche récapitulative listant toutes ses formes. Pour chaque forme, un système d'ajout/suivi au Shiny Living Dex.
Stack & contraintes
(à compléter selon tes choix techniques)
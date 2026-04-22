erDiagram
    UTILISATEUR ||--o{ OBTENTION : possède
    VERSION ||--o{ OBTENTION : "est possédée par"
    POKEMON ||--|{ VERSION : "a des"
    VERSION }o--o{ TAG : "est taggée via TAG_VERSION"
    TAG ||--o{ OBTENTION : "méthode d'obtention"
    OBTENTION ||--o{ OBTENTION_TAG : "est contextualisée par"
    TAG ||--o{ OBTENTION_TAG : "contextualise"
    UTILISATEUR ||--o{ POKEMON_FAVORI : "a des favoris"
    POKEMON ||--o{ POKEMON_FAVORI : "est favori de"
    VERSION ||--o{ POKEMON_FAVORI : "est la version favorite"

    UTILISATEUR {
        int id PK
        string username UK
        string email
        string password
        json roles
        datetime date_creation
        datetime date_modification
    }

    POKEMON {
        int numero PK
        string nom
        datetime date_creation
        datetime date_modification
    }

    VERSION {
        int id PK
        int numero_pokemon FK
        string nom
        string nom_fichier_image
        datetime date_creation
        datetime date_modification
    }

    TAG {
        int id PK
        string nom
        string categorie
        datetime date_creation
        datetime date_modification
    }

    TAG_VERSION {
        int id PK
        int id_version FK
        int id_tag FK
        datetime date_creation
        datetime date_modification
    }

    OBTENTION {
        int id PK
        int id_utilisateur FK
        int id_version FK
        int methode_obtention_id FK
        datetime date_obtention
        int iteration_avant_obtention
        text notes
        datetime date_creation
        datetime date_modification
    }

    OBTENTION_TAG {
        int id PK
        int id_obtention FK
        int id_tag FK
        datetime date_creation
        datetime date_modification
    }

    POKEMON_FAVORI {
        int id PK
        int id_utilisateur FK
        int id_pokemon FK
        int id_version FK
        datetime date_creation
    }
# Cadrage de conception — BlueDocs

## 1. Objet

BlueDocs est une application web Laravel de gestion documentaire. Elle permet de consulter et de rechercher des documents publiés, de les créer, modifier ou supprimer pour les utilisateurs authentifiés, et de gérer des comptes sous contrôle de la capacité `create-users`.

Cette documentation formalise la conception existante. Elle ne décrit pas une cible fonctionnelle supposée : les comportements sont tirés des routes, contrôleurs, modèles, vues, migrations, tests et du schéma MySQL observé.

## 2. Sources et périmètre d’observation

- Routes : `routes/web.php` et `routes/auth.php`.
- Métier : contrôleurs, requêtes de validation et modèles de `app/`.
- Persistance : migrations de `database/migrations/` et schéma MySQL interrogé via Laravel Boost.
- Comportements couverts : vues Blade et tests Pest.
- Versions observées : PHP 8.5, Laravel 13.31, moteur MySQL.
- Instantané de volumétrie au 23 octobre 2026 : 3 utilisateurs, 19 documents et 17 sections.

Les volumes sont indicatifs et peuvent évoluer. Les valeurs nominatives des enregistrements ne sont pas reproduites.

## 3. Démarche 2TUP appliquée

2TUP (Two-Track Unified Process) sépare l’étude fonctionnelle de l’étude technique, puis les fait converger dans la conception et la réalisation.

### Branche fonctionnelle

1. Identifier les acteurs et les frontières d’accès.
2. Décrire les cas d’utilisation : consultation, recherche, gestion des documents, gestion des sections, authentification et profil.
3. Modéliser le domaine documentaire et les parcours métier.

Livrables associés : cas d’utilisation, classes métier, séquences et activité.

### Branche technique

1. Décrire les composants présents : navigateur, vues Blade, routes et middleware Laravel, contrôleurs, Eloquent et MySQL.
2. Formaliser les entités réellement stockées, y compris les tables de support Laravel.
3. Décrire la topologie logique sans attribuer de fournisseur ou de serveur qui ne serait pas établi par le dépôt.

Livrables associés : modèle de données, composants et déploiement logique.

### Convergence

Les cas d’utilisation sont reliés aux contrôleurs/routes, aux modèles et aux tables persistées. Les écarts constatés entre le code/migrations et le schéma MySQL sont signalés plutôt que masqués. Le diagramme de démarche 2TUP montre cette convergence.

## 4. Acteurs et règles d’accès

| Acteur | Capacités constatées |
| --- | --- |
| Visiteur | Accueil, consultation d’un document par son slug, sommaire, recherche, connexion et demande de réinitialisation du mot de passe. |
| Utilisateur authentifié / éditeur | Liste, création, modification et suppression des documents; création et suppression de sections; profil, mot de passe et déconnexion. |
| Administrateur | Capacités de l’utilisateur authentifié et accès à l’écran et à l’action de création d’utilisateurs via la capacité `create-users`. |

La gestion des documents et des sections vérifie l’authentification, mais les routes observées n’imposent pas un rôle `admin` pour ces opérations. Le contrôle de création d’utilisateurs, lui, vérifie `role === 'admin'`.

## 5. Exigences fonctionnelles observées

| ID | Exigence | Routes ou composants |
| --- | --- | --- |
| F-01 | Consulter les pages d’accueil, le sommaire et un document par slug. Un slug inconnu peut produire un document virtuel en lecture, sans insertion en base. | `/`, `/sommaire`, `/{document}` |
| F-02 | Rechercher des documents par mots-clés, avec validation, extraits et pagination. | `/recherche` |
| F-03 | Lister, créer, mettre à jour et supprimer des documents. Le slug de création peut être fourni ou généré depuis le titre. | `/documents*` |
| F-04 | Créer et supprimer des sections après authentification. | `/sommaire/sections*` |
| F-05 | Se connecter et se déconnecter; limiter les tentatives de connexion. | `/login`, `/logout`, `LoginRequest` |
| F-06 | Créer un compte si l’utilisateur connecté satisfait `create-users`. | `/register` |
| F-07 | Modifier ou supprimer son compte, confirmer le mot de passe, vérifier l’adresse et réinitialiser le mot de passe. | Routes d’authentification et `/profile` |
| F-08 | Présenter un tableau de bord avec les derniers documents et connexions recensées. La route n’a pas de middleware d’authentification déclaré. | `/dashboard` |

## 6. Modèle métier et règles de persistance

- Un document porte un titre, un slug, du contenu HTML, un nom d’utilisateur conservé sous forme de texte et les dates de création/mise à jour. La migration rend le slug unique, mais l’index unique n’apparaît pas dans le schéma MySQL observé.
- `documents.updated_by` référence éventuellement l’utilisateur ayant effectué la dernière modification; la suppression de cet utilisateur laisse le document en place (`SET NULL`).
- `documents.user_name` est une attribution textuelle et ne constitue pas une clé étrangère.
- Le modèle `Section` déclare une relation plusieurs-à-plusieurs avec `Document` via `document_section`.
- La migration de création des sections crée cette table pivot avec une clé primaire composée et des suppressions en cascade.
- **Écarts de schéma à traiter :** l’inspection de la base MySQL courante n’a pas retourné `document_section`, alors que la migration, les modèles et les tests la supposent; l’index unique attendu pour `documents.slug` n’a pas non plus été trouvé. Le diagramme physique reflète la base observée; le diagramme de classes reflète les associations déclarées dans le code. La base et les migrations doivent être réalignées avant de considérer ces contraintes disponibles dans cet environnement.
- Le document ne possède pas de champ d’état métier. Le diagramme d’états décrit uniquement son cycle de persistance, pas un statut fonctionnel stocké.

## 7. Architecture logique

Le navigateur appelle les routes HTTP Laravel. Les middlewares `auth`, `guest` et `can:create-users` filtrent certaines requêtes. Les contrôleurs valident les entrées, utilisent les modèles Eloquent et retournent les vues Blade ou des redirections. Eloquent accède au schéma MySQL. La recherche est réalisée côté serveur sur les champs `title` et `content`, puis paginée par 15 résultats.

La création et la modification de documents émettent des journaux d’événement avec des identifiants et métadonnées de requête, sans inclure le contenu du document dans les messages observés.

## 8. Traçabilité conception

| Besoin | Cas d’utilisation | Séquence / activité | Classes et données |
| --- | --- | --- | --- |
| F-01 Consultation | Consulter un document | Cycle documentaire | `Document`, `documents` |
| F-02 Recherche | Rechercher des documents | Recherche | `DocumentSearchController`, `documents` |
| F-03 Gestion documentaire | Gérer les documents | Documents, cycle documentaire | `Document`, `User`, `documents` |
| F-04 Sections | Gérer les sections | Sections | `Section`, `Document`, `sections`, pivot attendu |
| F-05 Connexion | S’authentifier | Authentification | `User`, `sessions`, `users` |
| F-06 Comptes | Créer un utilisateur | Cas d’utilisation / contrôle d’accès | `User`, `users` |
| F-07 Profil et sécurité du compte | Gérer son compte | Routes Breeze | `User`, `users`, `password_reset_tokens` |
| F-08 Tableau de bord | Consulter le tableau de bord | Route de tableau de bord | `User`, `Document`, `users`, `documents` |

## 9. Tables persistantes

Le schéma observé contient onze tables : cinq tables métier ou d’authentification et six tables de support Laravel. La table pivot `document_section` décrite par une migration n’est pas présente dans cet instantané, et l’index unique attendu sur le slug manque également. Le détail des colonnes figure dans [02-dictionnaire-donnees.md](./02-dictionnaire-donnees.md).

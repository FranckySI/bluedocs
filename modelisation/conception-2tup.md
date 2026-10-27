# Conception BlueDocs selon 2TUP

## Périmètre

Ce document décrit les fonctionnalités BlueDocs et leur implémentation dans Laravel/MySQL. Le visiteur ne s'authentifie pas et dispose uniquement de la consultation publique. Les seuls rôles persistés sont `editeur` et `admin`.

Les diagrammes sont fournis dans des fichiers PlantUML séparés, utilisables avec l'extension PlantUML de VS Code. Ouvrir un fichier `.puml`, puis lancer la commande de prévisualisation PlantUML de l'extension.

## Démarche 2TUP

| Branche fonctionnelle | Branche technique |
|---|---|
| Consulter l'accueil, le sommaire, les documents et les résultats de recherche. | Routes web publiques, contrôleurs et vues Blade. |
| Authentifier un éditeur ou un administrateur. | Auth Laravel, sessions et rôle stocké dans `users.role`. |
| Créer, modifier et supprimer des documents. | Middleware `auth`, contrôleur Eloquent, validation HTTP et journalisation. |
| Réserver la création de comptes à l'administrateur. | Gate `create-users`, défini dans `AppServiceProvider`. |

La fusion des besoins et de l'architecture est présentée au moyen des cas d'utilisation, modèles de domaine et de données, séquences et composants.

## Acteurs et cas d'utilisation

Diagramme PlantUML : [2tup-cas-utilisation.puml](./2tup-cas-utilisation.puml).

Le visiteur peut consulter les pages publiques et rechercher des documents, sans s'authentifier. Les éditeurs et administrateurs authentifiés ont accès au tableau de bord et aux opérations documentaires. Seul l'administrateur peut créer des comptes, selon le Gate `create-users`.

## Modèle de domaine et diagramme de classes

Diagramme PlantUML : [2tup-classes-domaine.puml](./2tup-classes-domaine.puml).

Un document possède un slug unique et stable, utilisé pour ses URL publiques et ses routes d'édition. Le titre peut changer sans changer le slug. `updated_by` référence facultativement le dernier utilisateur ayant modifié le document, tandis que `user_name` conserve le nom d'affichage.

## Modèle logique et dictionnaire de données

Diagramme physique PlantUML : [2tup-modele-donnees.puml](./2tup-modele-donnees.puml).

### `users`

| Champ | Type MySQL | Contraintes / rôle |
|---|---|---|
| `id` | `bigint unsigned` | Clé primaire, auto-incrémentée. |
| `name` | `varchar(255)` | Nom affiché. |
| `email` | `varchar(255)` | Unique. |
| `role` | `varchar(255)` | Non nullable, défaut `editeur`; valeurs applicatives `editeur` ou `admin`. |
| `email_verified_at` | `timestamp` | Nullable. |
| `password` | `varchar(255)` | Mot de passe haché par Laravel. |
| `remember_token` | `varchar(100)` | Nullable. |
| `last_login_at` | `timestamp` | Nullable, actualisé à la connexion. |
| `created_at`, `updated_at` | `timestamp` | Timestamps Laravel, nullable. |

### `documents`

| Champ | Type MySQL | Contraintes / rôle |
|---|---|---|
| `id` | `bigint unsigned` | Clé primaire, auto-incrémentée. |
| `slug` | `varchar(255)` | Non nullable et unique; clé de route pour lecture, édition, mise à jour et suppression. |
| `title` | `varchar(255)` | Titre modifiable sans changer le slug. |
| `content` | `longtext` | Contenu du document. |
| `user_name` | `varchar(255)` | Nom de l'auteur/dernier éditeur affiché. |
| `updated_by` | `bigint unsigned` | Nullable; clé étrangère vers `users.id`, suppression utilisateur => `SET NULL`. |
| `created_at`, `updated_at` | `timestamp` | Timestamps Laravel, nullable. |

### Tables techniques

Le schéma comporte également `cache`, `cache_locks`, `failed_jobs`, `jobs`, `job_batches`, `migrations`, `password_reset_tokens` et `sessions`. Elles supportent Laravel et ne font pas partie du domaine documentaire.

## Diagrammes de séquence

- Authentification et mise à jour de la dernière connexion : [2tup-sequence-authentification.puml](./2tup-sequence-authentification.puml).
- Lecture publique et recherche : [2tup-sequence-lecture-recherche.puml](./2tup-sequence-lecture-recherche.puml).
- Modification par slug stable : [2tup-sequence-edition-document.puml](./2tup-sequence-edition-document.puml).

## Cycle de vie fonctionnel d'un document

Diagramme d'états PlantUML : [2tup-etats-document.puml](./2tup-etats-document.puml).

La base ne contient pas de statut, d'approbation ou de publication. Le diagramme représente les opérations réellement disponibles.

## Architecture applicative

Diagramme de composants PlantUML : [2tup-composants.puml](./2tup-composants.puml).

## Matrice d'accès

| Fonction / routes | Visiteur | `editeur` | `admin` |
|---|---:|---:|---:|
| Accueil, sommaire, recherche et lecture `/{slug}` | Oui | Oui | Oui |
| Tableau de bord `/dashboard` | Non | Oui | Oui |
| Liste `/documents` et opérations CRUD | Non | Oui | Oui |
| Profil et déconnexion | Non | Oui | Oui |
| Création d'utilisateurs (`can:create-users`) | Non | Non | Oui |

Les contrôles utilisent le middleware `auth` et le Gate `create-users`. Aucune Policy n'est déclarée. Les routes de lecture publique ne nécessitent pas de contrôle d'autorisation.

## Points corrigés et limites restantes

- Le middleware `auth` protège le tableau de bord et les opérations documentaires.
- La migration `update_user_role_default_to_editeur` convertit les anciens rôles `user`, puis fixe le rôle par défaut à `editeur`.
- Le modèle `Document` utilise `slug` comme clé de route. Les formulaires d'édition et les actions de liste génèrent leurs URL à partir du modèle ; le slug reste inchangé pendant la modification du titre.
- Une migration ajoute l'index unique sur `documents.slug` uniquement s'il manque. Les nouveaux slugs générés à partir d'un titre reçoivent un suffixe en cas de doublon.
- Les rôles `editeur` et `admin` ont les mêmes droits documentaires ; seul l'accès à la création des comptes est réservé à `admin`.

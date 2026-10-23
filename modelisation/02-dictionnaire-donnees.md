# Dictionnaire de données

## 1. Source et convention

Le tableau ci-dessous reprend le schéma MySQL de l’application interrogé le 23 octobre 2026. Les champs suivent le nom et le type retournés par l’introspection. Les colonnes notées `timestamps` désignent `created_at` et `updated_at` lorsqu’elles existent.

La base observée comprend 3 lignes dans `users`, 19 dans `documents` et 17 dans `sections`. Les contenus, noms, adresses e-mail et autres valeurs personnelles ne sont pas copiés. Les tables techniques ne sont pas exportées ligne par ligne.

## 2. Tables métier et comptes

### `users`

| Colonne | Type | Contraintes / usage |
| --- | --- | --- |
| `id` | bigint unsigned | PK, auto-incrément |
| `name` | varchar(255) | Nom affiché |
| `email` | varchar(255) | Unique |
| `role` | varchar(255) | Défaut `user`; l’application utilise `user` et `admin` |
| `email_verified_at` | timestamp | Nullable |
| `password` | varchar(255) | Mot de passe haché par le modèle |
| `remember_token` | varchar(100) | Nullable |
| `last_login_at` | timestamp | Nullable; affiché dans le tableau de bord |
| `created_at`, `updated_at` | timestamp | Nullable |

### `documents`

| Colonne | Type | Contraintes / usage |
| --- | --- | --- |
| `id` | bigint unsigned | PK, auto-incrément |
| `slug` | varchar(255) | Obligatoire; la migration ajoute une contrainte unique, absente de l’index observé |
| `title` | varchar(255) | Titre |
| `content` | longtext | Contenu documentaire, notamment HTML |
| `user_name` | varchar(255) | Attribution textuelle enregistrée à la création/mise à jour |
| `created_at`, `updated_at` | timestamp | Nullable |
| `updated_by` | bigint unsigned | Nullable; FK vers `users.id`, `ON DELETE SET NULL` |

### `sections`

| Colonne | Type | Contraintes / usage |
| --- | --- | --- |
| `id` | bigint unsigned | PK, auto-incrément |
| `title` | varchar(255) | Titre |
| `created_at`, `updated_at` | timestamp | Nullable |

### `document_section` — table attendue, absente de la base observée

La migration `2026_10_20_150320_create_sections_table.php` crée une table de liaison composée des colonnes `section_id` et `document_id`, toutes deux clés étrangères avec suppression en cascade et formant ensemble la clé primaire. Elle matérialise la relation plusieurs-à-plusieurs définie par `Section::documents()`.

Cette table n’a pas été trouvée dans le schéma MySQL observé. Elle n’est donc pas dessinée comme table existante dans le modèle physique. Les opérations Eloquent qui utilisent cette relation ne doivent pas être considérées opérationnelles sur cette base tant que le schéma n’a pas été régularisé.

## 3. Tables Laravel de session, cache, file d’attente et migrations

### `sessions`

| Colonne | Type | Contraintes / usage |
| --- | --- | --- |
| `id` | varchar(255) | PK |
| `user_id` | bigint unsigned | Nullable, indexé; pas de FK déclarée dans la migration |
| `ip_address` | varchar(45) | Nullable |
| `user_agent` | text | Nullable |
| `payload` | longtext | Données de session |
| `last_activity` | int | Indexé |

### `password_reset_tokens`

| Colonne | Type | Contraintes / usage |
| --- | --- | --- |
| `email` | varchar(255) | PK |
| `token` | varchar(255) | Jeton de réinitialisation |
| `created_at` | timestamp | Nullable |

### `cache`

| Colonne | Type | Contraintes / usage |
| --- | --- | --- |
| `key` | varchar(255) | PK |
| `value` | mediumtext | Valeur |
| `expiration` | bigint | Expiration |

### `cache_locks`

| Colonne | Type | Contraintes / usage |
| --- | --- | --- |
| `key` | varchar(255) | PK |
| `owner` | varchar(255) | Propriétaire du verrou |
| `expiration` | bigint | Expiration |

### `jobs`

| Colonne | Type | Usage |
| --- | --- | --- |
| `id` | bigint unsigned | PK, auto-incrément |
| `queue` | varchar(255) | File |
| `payload` | longtext | Charge utile |
| `attempts` | smallint unsigned | Nombre de tentatives |
| `reserved_at` | int unsigned | Réservation, nullable |
| `available_at` | int unsigned | Disponibilité |
| `created_at` | int unsigned | Création |

### `job_batches`

| Colonne | Type | Usage |
| --- | --- | --- |
| `id` | varchar(255) | PK |
| `name` | varchar(255) | Nom du lot |
| `total_jobs` | int | Nombre total |
| `pending_jobs` | int | En attente |
| `failed_jobs` | int | En échec |
| `failed_job_ids` | longtext | Identifiants en échec |
| `options` | mediumtext | Options, nullable |
| `cancelled_at` | int | Annulation, nullable |
| `created_at` | int | Création |
| `finished_at` | int | Fin, nullable |

### `failed_jobs`

| Colonne | Type | Usage |
| --- | --- | --- |
| `id` | bigint unsigned | PK, auto-incrément |
| `uuid` | varchar(255) | Identifiant de job |
| `connection` | varchar(255) | Connexion |
| `queue` | varchar(255) | File |
| `payload` | longtext | Charge utile |
| `exception` | longtext | Détail de l’échec |
| `failed_at` | timestamp | Date d’échec |

### `migrations`

| Colonne | Type | Usage |
| --- | --- | --- |
| `id` | int unsigned | PK, auto-incrément |
| `migration` | varchar(255) | Nom de migration |
| `batch` | int | Numéro de lot |

## 4. Relations et intégrité

| Relation | Implémentation attendue / observée |
| --- | --- |
| `users` → `documents` (éditeur de dernière mise à jour) | 1 utilisateur pour 0..n documents; `documents.updated_by` nullable; FK observée avec `ON DELETE SET NULL`. |
| `documents` ↔ `sections` | Plusieurs-à-plusieurs dans les modèles et migrations via `document_section`; table de liaison absente de la base observée. |
| Unicité du slug | Une migration ajoute l’index unique sur `documents.slug`, mais l’introspection de la base courante ne retourne que les index primaire et `updated_by`; l’unicité n’est donc pas garantie par le schéma observé. |
| `users` → `sessions` | `sessions.user_id` est nullable et indexé; la migration ne déclare pas de FK. |
| `documents.user_name` → `users` | Pas de relation d’intégrité : nom textuel, pas d’identifiant utilisateur. |

## 5. Index observés

- `users.email` : unique.
- `documents.updated_by` : index de clé étrangère. Aucun index sur `documents.slug` n’a été retourné, malgré la migration qui demande son unicité.
- `sessions.user_id` et `sessions.last_activity` : index non uniques.
- `cache.expiration` et `cache_locks.expiration` : index non uniques.
- `jobs.queue` : index non unique.
- `failed_jobs.uuid` : unique; `(connection, queue, failed_at)` : index composite non unique.
- Les clés primaires décrites dans les tables restent leurs index uniques principaux.

Consulter [03-modele-donnees.puml](./03-modele-donnees.puml) pour le schéma physique introspecté et [02-classes-domaine.puml](./02-classes-domaine.puml) pour le modèle objet déclaré par l’application.

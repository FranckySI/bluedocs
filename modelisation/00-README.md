# Dossier de conception — BlueDocs

Ce dossier rassemble la documentation de conception de BlueDocs selon l’approche 2TUP. Les diagrammes sont fournis en source PlantUML (`.puml`) afin de pouvoir être versionnés, modifiés et rendus.

## Documents

- [01-cadrage-2tup.md](./01-cadrage-2tup.md) — périmètre, exigences, acteurs, architecture et traçabilité 2TUP.
- [02-dictionnaire-donnees.md](./02-dictionnaire-donnees.md) — dictionnaire de données issu du schéma MySQL réel et des migrations.

## Diagrammes PlantUML

| Fichier | Contenu |
| --- | --- |
| [01-cas-utilisation.puml](./01-cas-utilisation.puml) | Acteurs et cas d’utilisation |
| [02-classes-domaine.puml](./02-classes-domaine.puml) | Modèle de classes métier et associations Eloquent |
| [03-modele-donnees.puml](./03-modele-donnees.puml) | Schéma physique observé de la base et tables techniques |
| [04-sequence-authentification.puml](./04-sequence-authentification.puml) | Connexion, limitation des tentatives et session |
| [05-sequence-documents.puml](./05-sequence-documents.puml) | Création, consultation, mise à jour et suppression |
| [06-sequence-recherche.puml](./06-sequence-recherche.puml) | Recherche paginée dans les documents |
| [07-sequence-sections.puml](./07-sequence-sections.puml) | Création et suppression d’une section |
| [08-activite-cycle-document.puml](./08-activite-cycle-document.puml) | Parcours de consultation et de gestion documentaire |
| [09-etats-document.puml](./09-etats-document.puml) | Cycle de persistance d’un document |
| [10-composants.puml](./10-composants.puml) | Architecture logique des composants |
| [11-deploiement-logique.puml](./11-deploiement-logique.puml) | Topologie logique, sans présumer de l’hébergeur |
| [12-demarche-2tup.puml](./12-demarche-2tup.puml) | Branches fonctionnelle et technique et convergence |

## Rendu des diagrammes

Avec PlantUML installé localement, depuis la racine du dépôt :

```powershell
plantuml modelisation\*.puml
```

La commande génère les rendus à côté des sources. Aucun service en ligne n’est nécessaire : les diagrammes contiennent des éléments d’architecture propres au projet.

## Périmètre et limites

Les diagrammes décrivent le code et la base interrogés le 23 octobre 2026. Le dictionnaire précise les écarts entre la base en cours d’exécution et les migrations. Les données personnelles des lignes ne sont pas reproduites dans cette documentation. Les diagrammes de déploiement sont logiques, car aucun environnement de production n’est décrit dans le dépôt. Aucun moteur PlantUML n’est installé dans l’environnement de travail; les sources restent prêtes à être rendues avec PlantUML localement.

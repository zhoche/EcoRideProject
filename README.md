# EcoRide

**EcoRide** est une application web de covoiturage éco‑responsable conçue pour favoriser la mobilité durable entre passagers et conducteurs, réduire l’empreinte carbone et encourager des comportements respectueux de l’environnement.

---

## Table des matières

1. [Fonctionnalités](#fonctionnalités)
2. [Stack technique](#stack-technique)
3. [Architecture du projet](#architecture-du-projet)
4. [Prérequis](#prérequis)
5. [Installation et configuration](#installation-et-configuration)
   - [Front-end (Angular)](#front-end-angular)
   - [Back-end (Symfony)](#back-end-symfony)
   - [Base de données](#base-de-données)
6. [Comptes de connexion](#comptes-de-connexion)
7. [Déploiement](#déploiement)
8. [Documentation supplémentaire](#documentation-supplémentaire)
9. [Contributions et bonnes pratiques Git](#contributions-et-bonnes-pratiques-git)

---

## Fonctionnalités

- **4 types d’utilisateurs** : *passager*, *conducteur*, *employé* et *administrateur*

- **Inscription & authentification** : création de compte, login sécurisé, gestion de session via `localStorage`

- **Recherche et filtrage** de trajets :

  - Recherche par ville et date
  - Filtres par aspect écologique (véhicule électrique)
  - Préférences

- **Gestion des trajets** :

  - Vue liste et détails complets (places restantes, avis, modèle de véhicule, préférences)
  - Participation au covoiturage avec validation des crédits
  - Historique, démarrage et clôture de trajet

- **Espace Conducteur** : ajout/modification de véhicules, saisie de trajets, suivi des places disponibles, historique

- **Espace Passager** : suivi des participations, validation de trajet et dépôt d’avis

- **Espace Employé** : modération des avis et gestion des incidents

- **Espace Administrateur** : création de comptes employés, suspension de comptes, statistiques et graphiques (trajets/jour, crédits gagnés)

---

## Stack technique

| Côté client     | Côté serveur | Base de données    | Outils et autres |
| --------------- | ------------ | ------------------ | ---------------- |
| Angular 19.2    | Symfony 6    | MySQL / PostgreSQL | Git, GitHub      |
| TypeScript      | PHP 8.4      |                    | Composer, npm    |
| SCSS Responsive | Doctrine ORM |                    | Thunder Client   |

---

## Architecture du projet

Le code est organisé en trois dossiers principaux :

```
/ecoride-project
├── src       # Application Angular
├── backend        # API Symfony 6 + Doctrine
└── database       # Scripts SQL 
```

Chaque application peut fonctionner indépendamment en local ou être déployée sur des plateformes dédiées.

---

## Prérequis

- Angular CLI (installé globalement via `npm install -g @angular/cli`, nécessite `npm` et `Node.js` en arrière-plan)
- PHP >= 8.4
- Composer
- Symfony CLI ou serveur web local (Apache)
- Base de données : MySQL ou PostgreSQL

---

## Installation et configuration

### Front-end (Angular)

1. Cloner le dépôt et se positionner dans le dossier racine du projet 

```bash
   git clone https://github.com/zhoche/EcoRideProject.git 
   cd EcoRideProject
```

2. Installer les dépendances
```bash
   npm install
```

3. Générer le build de production
```bash
   npm run build
```

4. Démarrer le serveur Node
```bash
   npm run start
```

5. Ouvrir l’application dans votre navigateur (par défaut le serveur écoute sur le port 8080): `http://localhost:8080`

### Back-end (Symfony)

> Le client Symfony CLI est recommandé pour simplifier la gestion du serveur et des commandes. Et doit être installé via ce lien : [Symfony CLI](https://symfony.com/download).

1. Se positionner dans le dossier `backend`
```bash
   cd backend
```

2. Installer les dépendances PHP via Composer
```bash
   composer install
```

3. Copier et adapter le fichier d’environnement
```bash
   cp .env .env.local
```

4. Lancer le serveur Symfony
```bash
symfony server:start --port=8000
```

### Base de données

Les scripts SQL et fixtures se trouvent dans le dossier sql. Vous pouvez importer les fichiers `avis.sql`, `user.sql`, `ride.sql` et `vehicule.sql` via votre SGBD.

---

## Comptes de connexion

Pour vous connecter rapidement avec chaque profil, utilisez les comptes suivants :

| Profil             | Email                                                  | Mot de passe  |
| ------------------ | ------------------------------------------------------ | ------------- |
| **Passenger**      | [passenger@example.com](mailto\:passenger@example.com) | Testpass1234! |
| **Driver**         | [driver1@example.com](mailto\:driver1@example.com)     | Testpass1234! |
| **Employé**        | [employee2@example.com](mailto\:employee2@example.com) | Testpass1234! |
| **Administrateur** | [admin@ecoride.local](mailto\:admin@ecoride.local)     | Admin1234!    |

---

## Déploiement

- **Front-end** : déployé sur Heroku ([**https://zh-ecoride-frontend-7b5170b8d71e.herokuapp.com/home**](https://zh-ecoride-frontend-7b5170b8d71e.herokuapp.com/home))
- **Back-end** : déployé sur Render ([**https://ecoride-back-xm7y.onrender.com**](https://ecoride-back-xm7y.onrender.com)) via un conteneur Docker
- **Base de données** : hébergée sur Render (postgresql://ecoride\_user:[fpfkEILKgZzmqs0jHCakmSvJKW7VPinV@dpg-d1ro52p5pdvs73ec3i5g-a.frankfurt-postgres.render.com]

---

## Documentation supplémentaire

- [Charte graphique](documentation/EcoRide_CharteGraphique_2025_HOCHE.pdf)
- [Maquettes Desktop](documentation/EcoRide_MaquettesDesktop_2025_HOCHE.pdf)
- [Maquettes Mobile](documentation/EcoRide_MaquettesMobile_2025_HOCHE.pdf)
- [Plan de l’application](documentation/EcoRide_PlanAppWeb_2025_HOCHE.pdf)
- [TP DWWM (Nov-Déc 25)](documentation/TP_DWWM_NovDec25_copiearendre_HOCHE_Zoe.doc)

---

## Contributions et bonnes pratiques Git

1. **Branches** :
   - `main` : version stable en production
   - `develop` : intégration des nouvelles fonctionnalités
   - `feature/*` : chaque nouvelle US ou ticket
2. **Workflow** :
   - Créer une branche `feature/xxx` depuis `develop`
   - Committer et pusher régulièrement
   - Ouvrir une Pull Request vers `develop`
   - Revue de code, tests et merge
   - Après validation de `develop`, merge sur `main`

Merci d’accompagner chaque PR d’une description claire et de liens vers le document de gestion Notion si nécessaire : [**https://www.notion.so/EcoRide-1d78f19f15ce80608311c334835dd259?source=copy\_link**](https://www.notion.so/EcoRide-1d78f19f15ce80608311c334835dd259?source=copy_link).

---
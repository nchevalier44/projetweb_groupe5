# Solar Manager - Groupe 5

## Présentation du projet

**Solar Manager** est une application web de gestion des installations photovoltaïques chez les particuliers. Elle permet d'afficher la liste des installations, d'obtenir des statistiques détaillées et de visualiser la position géographique des installations sur une carte interactive.

## Table des matières

- [Présentation du projet](#présentation-du-projet)
- [Objectifs](#objectifs)
- [Architecture du projet](#architecture-du-projet)  
  - [Structure générale](#structure-générale)  
  - [Technologies utilisées](#technologies-utilisées)
- [Fonctionnalités](#fonctionnalités)  
  - [Interface publique (Front-end)](#interface-publique-front-end)  
    - [1. Page d'accueil](#1-page-daccueil)  
    - [2. Recherche avancée](#2-recherche-avancée)  
    - [3. Résultats de recherche](#3-résultats-de-recherche)  
    - [4. Détail d'installation](#4-détail-dinstallation)  
    - [5. Carte interactive](#5-carte-interactive)  
  - [Interface d'administration (Back-end)](#interface-dadministration-back-end)  
    - [1. Accueil administrateur](#1-accueil-administrateur)  
    - [2. Gestion des installations](#2-gestion-des-installations)
- [Base de données](#base-de-données)  
  - [Modélisation](#modélisation)  
  - [Sources de données](#sources-de-données)  
  - [Import des données](#import-des-données)
- [API REST](#api-rest)  
  - [Endpoints principaux](#endpoints-principaux)  
    - [Installations](#installations)  
    - [Installateurs](#installateurs)  
    - [Localisation](#localisation)  
    - [De nombreux autres endpoint](#de-nombreux-autres-endpoint)
- [Installation et déploiement](#installation-et-déploiement)  
  - [Prérequis](#prérequis)  
  - [Étapes d'installation](#étapes-dinstallation)
- [Équipe de développement](#équipe-de-développement)  
  - [Développeurs](#développeurs)  
  - [Outils de collaboration](#outils-de-collaboration)
- [Licence](#licence)

### Objectifs
- Gestion complète des installations photovoltaïques particulières
- Visualisation statistique des données
- Interface de recherche avancée avec filtres
- Cartographie interactive des installations
- Interface d'administration pour la gestion des données

---

## Architecture du projet

### Structure générale
```
.
├── api
│   └── solar_manager"
│       ├── annees
│       │   └── index/
│       ├── database/
│       ├── departements
│       │   └── index/
│       ├── functions_annees/
│       ├── functions_departements/
│       ├── functions_installateurs/
│       ├── functions_installations/
│       ├── functions_localisations/
│       ├── functions_onduleurs/
│       ├── functions_panneaux/
│       ├── functions_regions/
│       ├── functions_villes/
│       ├── installateurs
│       │   └── index/
│       ├── installations
│       │   └── index/
│       ├── localisations
│       │   └── index/
│       ├── onduleurs
│       │   ├── index/
│       │   ├── marques
│       │   │   └── index/
│       │   └── modeles
│       │       └── index/
│       ├── panneaux
│       │   ├── index/
│       │   ├── marques
│       │   │   └── index/
│       │   └── modeles
│       │       └── index/
│       ├── regions
│       │   └── index/
│       └── villes
│           └── index/
├── back  #Partie administrateur
│   ├── ajout/
│   ├── carte/
│   ├── details/
│   ├── index/
│   ├── login/
│   ├── logout/
│   ├── modif/
│   └── recherche/
├── bdd
│   ├── communes-france-2024-limite.csv
│   ├── data.csv
│   ├── mcd.mcd
│   ├── mcd.png
│   ├── python
│   │   ├── generateSQLInstal.py
│   │   ├── generateSQL.py
│   │   └── villecodeinseemap.txt
│   └── sql
│       ├── data.sql
│       └── model.sql
├── html #Partie utilisateur
│   ├── carte/
│   ├── details/
│   ├── footer.html
│   ├── index/
│   ├── navbar/
│   └── recherche/
├── images
│   ├── logo.png
│   └── panneau-solaire-icone.png
├── README.md
├── scripts
│   ├── ajout.js
│   ├── back.js
│   ├── carte.js
│   ├── detailsback.js
│   ├── details.js
│   ├── icone.js
│   ├── indexback.js
│   ├── index.js
│   ├── modif.js
│   ├── rechercheback.js
│   ├── recherche.js
│   └── utils.js
└── styles
    ├── ajout.css
    ├── carte.css
    ├── details.css
    ├── footer.css
    ├── index.css
    ├── modif.css
    ├── navbar.css
    └── recherche.css
```

---

## Technologies utilisées

### Frontend
- **HTML5** - Structure des pages
- **CSS3** - Stylisation et responsive design
- **Bootstrap** - Framework CSS pour l'interface
- **JavaScript** - Interactions côté client
- **AJAX** - Communications asynchrones
- **Leaflet.js** - Cartographie interactive (OpenStreetMap)

### Backend
- **PHP 7.4/8.2** - Logique serveur et API
- **PDO** - Accès aux données
- **JSON** - Format d'échange de données

### Base de données
- **MySQL 5.7**
- **JMerise** - Modélisation

### Serveur
- **Apache 2** - Serveur web
- **Machine virtuelle** - Environnement de déploiement

---

## Fonctionnalités

### Interface publique (Front-end)

#### 1. Page d'accueil
- Présentation du site et de ses objectifs
- Statistiques générales :
  - Nombre total d'installations
  - Répartition par années
  - Répartition par régions
  - Nombre d'installateurs
  - Marques d'onduleurs et panneaux

#### 2. Recherche avancée
- Filtres par :
  - Marque d'onduleur (20 items max)
  - Marque de panneaux (20 items max)
  - Département (20 départements aléatoires)
- Affichage des résultats en tableau
- Accès aux détails d'une installation

#### 3. Résultats de recherche
Affichage des installations correspondantes avec :
- Date d'installation
- Nombre de panneaux
- Surface
- Puissance crête
- Localisation
- Lien vers le détail
- Affichage paginé (limite 99 items)

#### 4. Détail d'installation
- Affichage complet de toutes les données
- Page dédiée accessible depuis les résultats
- Carte interactive

#### 5. Carte interactive
- Formulaire de sélection :
  - Année d'installation (20 options)
  - Département (20 départements aléatoires)
- Affichage sur carte OpenStreetMap
- Marqueurs avec informations (localité, puissance) et icone personnalisé
- Liens vers pages de détail

### Interface d'administration (Back-end)

#### 1. Accueil administrateur

- Même fonctionnalité que les pages publiques

#### 2. Gestion des installations
- Ajout de nouvelles installations
- Modification des installations existantes
- Suppression d'installations
---

## Base de données

### Modélisation
- **MCD** (Modèle Conceptuel de Données)
- **MPD** (Modèle Physique de Données)
- Respect des règles de normalisation

### Sources de données
- `data.csv` - Données des installations
- `communes-france-2024-limite.csv` - Données géographiques

### Import des données
Scripts python automatisé pour peupler la base depuis les fichiers CSV fournis.

---

## API REST

### Endpoints principaux

#### Installations
- `GET /api/solar_manager/installations/` - Liste des installations
- `POST /api/solar_manager/installations/` - Création d'installation
- `PUT /api/solar_manager/installations/` - Modification d'installation
- `DELETE /api/solar_manager/installations/` - Suppression d'installation

#### Installateurs
- `GET /api/solar_manager/installateurs/` - Liste des installateurs
- `GET /api/solar_manager/installateurs/?id=${id}` - Récupération par id
- `GET /api/solar_manager/installateurs/?Installateur=${Installateur}` - Récupération par nom
- `POST /api/solar_manager/installateurs/` - Création d'un installateur

#### Localisation
- `GET /api/solar_manager/localisations/` - Liste des localisations
- `GET /api/solar_manager/localisations/?id=${id}` - Récupération par id
- `POST /api/solar_manager/localisations/` - Création de localisation
- `PUT /api/solar_manager/localisations/?id=${id}` - Modification de la localisation

### De nombreux autres endpoint

Des endpoints afin de lister, récupérer, modifier, supprimer pour les modèles/marques des panneaux/onduleurs.

---

## Installation et déploiement

### Prérequis
- Apache 2
- PHP 7.4 ou 8.2
- PostgreSQL 11 ou MySQL 5.7
- Machine virtuelle configurée

### Étapes d'installation

1. **Cloner le repository**
```bash
git clone https://github.com/nchevalier44/projetweb_groupe5
cd projetweb_groupe5
```

2. **Configuration de la base de données**
```bash
# Créer la base de données
# Importer le schéma
mysql -u root -p < bdd/model.sql
```

3. **Import des données**
```bash
php bdd/sql/data.sql
```

---

## Équipe de développement

### Développeurs
Projet réalisé par :
- Nathan Chevalier
- Édouard Jouhri
- Maxime You

### Outils de collaboration
- **Git** - Gestionnaire de versions
- **Figma** - Maquettes et design
- **lien** -https://github.com/nchevalier44/projetweb_groupe5

---

## Licence

Projet académique - CIR2 ISEN Ouest 2024/2025

---
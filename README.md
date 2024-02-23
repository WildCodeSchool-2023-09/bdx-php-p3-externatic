# Projet Externatic

Bienvenue dans le projet Externatic, une plateforme web dédiée à la gestion du recrutement. Cette application offre une expérience intuitive et efficace tant pour les candidats que pour les recruteurs.


## Fonctionnalités Principales

**Authentification et Gestion des Comptes Utilisateurs :**
  - Enregistrement des utilisateurs (candidats ou recruteurs).
  - Connexion sécurisée avec gestion de session.
    
**Gestion des Offres d’Emploi :**
  - Création, modification et suppression d’offres par les recruteurs.
  - Consultation des offres et postulation par les candidats.
    
**Gestion des Candidatures :**
  - Visualisation des candidatures par les recruteurs.
  - Suivi de l’état des candidatures par les candidats.

**Favoris :**
  - Marquage et gestion des offres favorites par les candidats.

**Barre de Recherche :**
  - Possibilité pour les candidats de chercher des jobs.
  - Possibilité pour les entreprises de chercher des candidats.


## Environnement Technique

**Back-end :** Symfony 6.4, PHP 8.1
**Front-end :** Webpack Encore, Stimulus.js, SASS, Bootstrap-icons
**Base de Données :** Doctrine, Modèle conceptuel de données (Merise)


## Installation

1. Clonez ce dépôt : `git clone [URL_DU_REPO]`
2. Installez les dépendances PHP : `composer install`
3. Installez les dépendances front-end : `yarn install`
4. Configurez les variables d’environnement : copiez `.env.dist` en `.env` et ajustez si nécessaire.
5. Créez la base de données : `php bin/console doctrine:database:create`
6. Appliquez les migrations : `php bin/console doctrine:migrations:migrate`
7. Lancez le serveur de développement : `symfony serve`


## Utilisation

Une fois le projet installé et configuré, vous pouvez accéder à l’application via le navigateur à l’adresse http://localhost:8000.


## Contribution

Vous pouvez contribuer à ce projet en suivant les étapes suivantes :
Forker le projet
Créer une branche (git checkout -b feature/nouvelle-fonctionnalite)
Commiter les changements (git commit -am 'Ajouter une nouvelle fonctionnalité')
Pousser la branche (git push origin feature/nouvelle-fonctionnalite)
Ouvrir une Pull Request


Pour découvrir notre projet en ligne :

https://2023-09-p3-externatic.bordeaux-jlg.wilders.dev/

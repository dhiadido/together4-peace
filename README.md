# readme-together4peace

## Description du projet

**Together4Peace** est une application web développée dans un cadre universitaire autour du thème **Société, Paix et Inclusion**. Le projet vise à sensibiliser les utilisateurs à ces valeurs à travers des interactions participatives, un quiz éducatif et un espace d’échange, tout en proposant un accompagnement par des spécialistes lorsque cela est nécessaire.

Le site permet aux utilisateurs de :

* Partager et consulter des témoignages dans un espace de discussion,
* Tester leurs connaissances via un quiz thématique,
* Accéder automatiquement à des articles et des offres de spécialistes si leur score au quiz est inférieur à 40 %,
* S’inscrire et participer activement à la communauté Together4Peace.

Un espace **administrateur** est également intégré pour gérer l’ensemble du contenu via une architecture **MVC** et des fonctionnalités **CRUD**.

---

## Table des matières

* [Fonctionnalités](#fonctionnalités)
* [Technologies utilisées](#technologies-utilisées)
* [Architecture du projet](#architecture-du-projet)
* [Installation](#installation)
* [Utilisation](#utilisation)
* [Espace Administrateur](#espace-administrateur)
* [Équipe du projet](#équipe-du-projet)
* [Démonstration](#démonstration)
* [Licence](#licence)

---

## Fonctionnalités

### 🌍 Front Office (Utilisateur)

* **Témoignages** :

  * Espace de discussion permettant aux utilisateurs de partager leurs expériences et points de vue.

* **Quiz** :

  * Quiz à choix multiples sur le thème *Société, Paix et Inclusion*.
  * Le score est calculé automatiquement.
  * Si le score est **inférieur à 40 %**, le système affiche :

    * des articles éducatifs,
    * des offres proposées par des spécialistes.

* **Participants** :

  * Inscription des personnes souhaitant participer et s’engager dans la plateforme.

* **Authentification** :

  * Inscription et connexion sécurisées des utilisateurs.

---

## Technologies utilisées

* **HTML** : structure des pages web
* **CSS** : mise en forme et design
* **JavaScript** : interactions dynamiques côté client
* **PHP** : logique métier et traitement côté serveur
* **MySQL** : gestion de la base de données
* **phpMyAdmin** : administration de la base de données
* **XAMPP** : environnement de développement local

---

## Architecture du projet

Le projet adopte une architecture **MVC (Model - View - Controller)** afin d’assurer une bonne organisation du code et une maintenance facilitée.

Structure principale :

* **Controller** : gestion de la logique applicative (QuizController, UserController, AdminController, etc.)
* **Model** : gestion des entités et accès aux données (User, Quiz, Article, Offre, Témoignage, Participant)
* **View** : interface utilisateur

  * **Frontoffice** : interface destinée aux utilisateurs
  * **Backoffice** : interface d’administration

---

## Installation

1. Cloner le repository GitHub :

```bash
git clone https://github.com/dhiadido/together4-peace.git
```

2. Copier le projet dans le dossier XAMPP :

```
C:\xampp\htdocs\Projet2
```

3. Accéder au dossier du projet :

```bash
cd C:/xampp/htdocs/Projet2
```

4. Créer la base de données :

* Nom de la base de données : `together4peace`
* Importer le fichier SQL : `together4peace.sql`

5. Configurer la connexion à la base de données dans le fichier PHP :

```php
$host = "localhost";
$dbname = "together4peace";
$user = "root";
$password = "";
```

6. Démarrer **Apache** et **MySQL** depuis XAMPP.

---

## Utilisation

* Ouvrir un navigateur web
* Accéder au site via :

```
http://localhost/Projet2
```

* Créer un compte utilisateur ou se connecter
* Participer aux témoignages
* Réaliser le quiz et consulter les recommandations affichées si le score est inférieur à 40 %
* Accéder à l’espace administrateur avec un compte autorisé

---

## Espace Administrateur

L’espace **Administrateur** permet la gestion complète de la plateforme avec des fonctionnalités **CRUD (Create, Read, Update, Delete)** sur les modules suivants :

* Témoignages
* Quiz
* Articles
* Offres de spécialistes
* Participants
* Utilisateurs
* Administrateurs

Cet espace est sécurisé et réservé aux administrateurs.

---

## Équipe du projet

* Ghassen Tounsi
* Dhiaeddine Boujemaa
* Ajroud Fakhreddine
* Mohamed Klabi
* Kadidiatou Diakite

---

## Licence

Ce projet a été réalisé dans un **cadre universitaire** et est destiné à un **usage pédagogique uniquement**.

Toute utilisation ou modification du code doit mentionner les auteurs du projet.

BIBLIOTHÈQUE EN LIGNE - GUIDE D'INSTALLATION COMPLET
====================================================

PRÉREQUIS
---------
- XAMPP installé sur votre machine
- Navigateur web moderne (Chrome, Firefox, Edge)
- Connexion Internet pour télécharger XAMPP

📥 ÉTAPE 1 : INSTALLATION DE XAMPP
----------------------------------

1. Téléchargez XAMPP depuis : https://www.apachefriends.org/fr/index.html
2. Lancez l'installateur et suivez les étapes :
   - Sélectionnez les composants : Apache, MySQL, PHP, phpMyAdmin
   - Choisissez le dossier d'installation : C:\xampp\ (recommandé)
   - Terminez l'installation

3. Démarrez XAMPP et activez les services :
   - Ouvrez le Panneau de Contrôle XAMPP
   - Cliquez sur "Start" pour Apache
   - Cliquez sur "Start" pour MySQL
   - Les deux doivent afficher "Running" en vert

4. Testez l'installation :
   - Ouvrez votre navigateur
   - Allez à : http://localhost/
   - Vous devriez voir la page d'accueil XAMPP

🗂️ ÉTAPE 2 : CRÉATION DE LA STRUCTURE DES DOSSIERS
--------------------------------------------------

1. Allez dans le dossier : C:\xampp\htdocs\

2. Créez un nouveau dossier nommé : bibliotheque_xampp

3. Dans ce dossier, créez cette structure exacte :

   bibliotheque_xampp/
   ├── index.html
   ├── results.php
   ├── details.php
   ├── wishlist.php
   ├── admin.php
   ├── css/
   │   └── style.css
   ├── js/
   │   └── script.js
   ├── php/
   │   ├── config.php
   │   ├── search.php
   │   └── crud.php
   └── README.txt

4. Copiez-collez le contenu de chaque fichier comme indiqué dans les instructions.

🐘 ÉTAPE 3 : CRÉATION DE LA BASE DE DONNÉES
-------------------------------------------

1. Allez sur : http://localhost/phpmyadmin

2. Créez la base de données :
   - Cliquez sur "Nouvelle base de données"
   - Nom : bibliotheque_en_ligne
   - Interclassement : utf8mb4_general_ci
   - Cliquez sur "Créer"

3. Créez les tables en exécutant ce code SQL :

-- Table Livres
CREATE TABLE Livres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(100) NOT NULL,
    auteur VARCHAR(100) NOT NULL,
    description TEXT,
    maison_edition VARCHAR(100),
    nombre_exemplaire INT DEFAULT 1
);

-- Table Lecteurs
CREATE TABLE Lecteurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

-- Table Liste_Lecture
CREATE TABLE Liste_Lecture (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_livre INT,
    id_lecteur INT,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY (id_livre) REFERENCES Livres(id),
    FOREIGN KEY (id_lecteur) REFERENCES Lecteurs(id)
);

4. Insérez des données d'exemple :

-- Livres
INSERT INTO Livres (titre, auteur, description, maison_edition, nombre_exemplaire) VALUES
('Le Petit Prince', 'Antoine de Saint-Exupéry', 'Conte poétique et philosophique sous l''apparence d''un conte pour enfants.', 'Gallimard', 5),
('1984', 'George Orwell', 'Roman dystopique sur un régime totalitaire et la surveillance de masse.', 'Harcourt Brace', 3),
('L''Étranger', 'Albert Camus', 'Roman philosophique sur l''absurdité de la condition humaine.', 'Gallimard', 4),
('Harry Potter à l''école des sorciers', 'J.K. Rowling', 'Premier tome de la saga Harry Potter.', 'Bloomsbury', 7),
('Orgueil et Préjugés', 'Jane Austen', 'Roman sur les mœurs de la société anglaise au début du XIXe siècle.', 'T. Egerton', 2);

-- Lecteurs
INSERT INTO Lecteurs (nom, prenom, email) VALUES
('Dupont', 'Jean', 'jean.dupont@email.com'),
('Martin', 'Marie', 'marie.martin@email.com');

-- Liste de lecture
INSERT INTO Liste_Lecture (id_livre, id_lecteur, date_emprunt) VALUES
(1, 1, '2023-11-01'),
(3, 1, '2023-11-05');

🔧 ÉTAPE 4 : CONFIGURATION
--------------------------

1. Ouvrez le fichier : php/config.php

2. Vérifiez les paramètres de connexion :
   - DB_HOST = 'localhost' (correct)
   - DB_USER = 'root' (correct pour XAMPP)
   - DB_PASS = '' (mot de passe vide pour XAMPP)
   - DB_NAME = 'bibliotheque_en_ligne' (correct)

3. Si vous avez modifié le mot de passe MySQL dans XAMPP, modifiez DB_PASS.

🚀 ÉTAPE 5 : LANCEMENT DU SITE
------------------------------

1. Assurez-vous que XAMPP est démarré (Apache + MySQL)

2. Ouvrez votre navigateur

3. Allez à : http://localhost/bibliotheque_xampp/

4. Vous devriez voir la page d'accueil de la bibliothèque !

📱 FONCTIONNALITÉS DISPONIBLES
------------------------------

✅ PAGE D'ACCUEIL (index.html)
- Présentation de la bibliothèque
- Formulaire de recherche
- Interface responsive

✅ RECHERCHE (results.php)
- Recherche par titre ou auteur
- Affichage des résultats en grille
- Liens vers les détails

✅ DÉTAILS DES LIVRES (details.php)
- Informations complètes du livre
- Ajout à la liste de lecture
- Description formatée

✅ LISTE DE LECTURE (wishlist.php)
- Consultation des livres empruntés
- Suppression de la liste
- Dates d'emprunt

✅ ADMINISTRATION (admin.php)
- Ajout de nouveaux livres
- Suppression de livres
- Tableau de gestion

🛠️ TEST COMPLET DU SYSTÈME
---------------------------

1. TEST DE RECHERCHE :
   - Allez sur la page d'accueil
   - Recherchez "Prince"
   - Vérifiez que "Le Petit Prince" apparaît

2. TEST DES DÉTAILS :
   - Cliquez sur "Voir détails" pour un livre
   - Vérifiez que toutes les informations s'affichent
   - Testez le bouton "Ajouter à ma liste"

3. TEST LISTE DE LECTURE :
   - Allez dans "Ma liste de lecture"
   - Vérifiez que les livres ajoutés s'affichent
   - Testez le bouton "Retirer"

4. TEST ADMINISTRATION :
   - Allez dans "Administration"
   - Ajoutez un nouveau livre
   - Vérifiez qu'il apparaît dans la recherche


🎯 INFORMATIONS TECHNIQUES
--------------------------

- Développé avec : HTML5, CSS3, JavaScript, PHP, MySQL
- Framework : Aucun (code natif)
- Responsive : Oui
- Compatibilité : Tous navigateurs modernes
- Serveur : Apache (XAMPP)
- Base de données : MySQL



📊 STRUCTURE DE LA BASE DE DONNÉES
----------------------------------

Livres :
- id (INT, Primary Key, Auto Increment)
- titre (VARCHAR 100)
- auteur (VARCHAR 100)
- description (TEXT)
- maison_edition (VARCHAR 100)
- nombre_exemplaire (INT)

Lecteurs :
- id (INT, Primary Key, Auto Increment)
- nom (VARCHAR 100)
- prenom (VARCHAR 100)
- email (VARCHAR 100, Unique)

Liste_Lecture :
- id (INT, Primary Key, Auto Increment)
- id_livre (INT, Foreign Key)
- id_lecteur (INT, Foreign Key)
- date_emprunt (DATE)
- date_retour (DATE)


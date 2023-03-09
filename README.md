# SaeQuiz

## Description
Application web pour répondre à des questionnaires

## Prérequis
- PHP 8.2 ou supérieur
- MariaDB 10.11 ou supérieur

## Installation
Pour pouvoir se connecter à la base de données il faut changer les données du fichier connect.php

Il faut changer les données suivantes :
```php
    $username = "root"; // vos identifiants
    $password = "root"; // votre mot de passe
    $dbname = "mabdd"; // le nom de votre base de données
    $host = 'localhost'; // l'adresse de votre base de données

    $bdd = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $username, $password); // /!\ port 3307
```
Attention le port 3307 est celui de MON MariaDB, alors que le port de base est le 3306. Si vous avez un port différent, il faut le changer.

## Utilisation
Pour utiliser le site, il faut se connecter avec le compte admin :
Il faut créer l'admin à la main dans la base de données.
```sql
-- Voici un exemple de requête pour créer un admin
-- Attention, le type de compte doit être en majuscule et laissé tel quel pour créer un admin
INSERT INTO USER(PSEUDO, MAIL, MDP, TYPEDECOMPTE) VALUES('BRYAN', 'admin@admin.com', 'ADMIN', 'ADMIN');
```
Sinon pour créer un compte, il faut se rendre sur la page d'inscription et remplir les champs.

Pour créer il faut insérer les données suivantes :
```sql
-- Voici un exemple de creation de questionnaire
INSERT INTO QUESTIONNAIRE (NOM_QUESTIONAIRE) VALUES ('Questionnaire 1');
INSERT INTO QUESTIONNAIRE (NOM_QUESTIONAIRE) VALUES ('Questionnaire 2');
INSERT INTO QUESTIONNAIRE (NOM_QUESTIONAIRE) VALUES ('Questionnaire 3');
```
Pour créer des questions, il faut insérer les données suivantes :
```sql
-- Voici un exemple de creation de question
INSERT INTO QUESTION (QUESTIONNAIRE_ID, QUESTION) VALUES (1, 'Combien de temps dure une journée ?');

INSERT INTO QUESTION (QUESTIONNAIRE_ID, QUESTION) VALUES (2, 'Quelle est la valeur de 10 km ?');
```
Enfin pour créer des réponses, il faut insérer les données suivantes :
```sql
-- Voici un exemple de creation de reponse
INSERT INTO REPONSE (QUESTION_ID, QUESTIONNAIRE_ID, REP, CORRECT) VALUES (1, 1, '24h', TRUE);
INSERT INTO REPONSE (QUESTION_ID, QUESTIONNAIRE_ID, REP, CORRECT) VALUES (1, 1, '12h', FALSE);
INSERT INTO REPONSE (QUESTION_ID, QUESTIONNAIRE_ID, REP, CORRECT) VALUES (1, 1, '1h', FALSE);

INSERT INTO REPONSE (QUESTION_ID, QUESTIONNAIRE_ID, REP, CORRECT) VALUES (4, 2, '10 000 m', TRUE);
INSERT INTO REPONSE (QUESTION_ID, QUESTIONNAIRE_ID, REP, CORRECT) VALUES (4, 2, '1 000 m', FALSE);
INSERT INTO REPONSE (QUESTION_ID, QUESTIONNAIRE_ID, REP, CORRECT) VALUES (4, 2, '100 000 m', FALSE);
```
### Specificité de la table USER
La table USER contient les données suivantes :
- ID : Identifiant unique de l'utilisateur
- PSEUDO : Pseudo de l'utilisateur
- MAIL : Mail de l'utilisateur (unique)
- MDP : Mot de passe de l'utilisateur
- TYPEDECOMPTE : Type de compte de l'utilisateur (ADMIN ou USER)

Pour remplir la BD, il faut faire ces deux commandes : 
```sql
source ./creation.slq
source ./insertion.sql
```

Pour lancer un serveur local, il faut faire la commande suivante :
```bash
# Linux / Windows
php -S localhost:8000
# ou MacOS
php -S localhost:8000 -t public
```
## Fonctionnalités
- Création de compte
- Connexion
- Déconnexion
- Suppression de quiz
- Repondre au quizz
- Exporter les questionnaire en JSON
- Importer les questionnaire en JSON (en cours)

## Les pages
Voici les pages visibles du site :
- index.php : Page de connexion
- home.php : Page avec les questionnaires disponibles
- qcm.php : Page des questions
- reponse.php : Page des résultats
- inscription.php : Page d'inscription
- deconnexion.php : Page de déconnexion
- menu.php : Menu du site


Voici les pages non visibles du site :
- connect.php : Page de connexion à la base de données
- inscription_traitement.php : Page de traitement de l'inscription
- modification_traitement.php : Page de traitement de la modification du compte
- connexion.php : Page de traitement de la connexion

## Les classes
Voici les classes du site :
- User : Classe pour les utilisateurs
- Questionnaire : Classe pour les questionnaires
- Question : Classe pour les questions
- Reponse : Classe pour les réponses






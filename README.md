# Projet de Quote Machine ![logo](https://img.icons8.com/dusk/64/000000/bookmark.png)

## Installation

Clonage du projet :

    $ git clone https://aresc002@iut-info.univ-reims.fr/gitlab/aresc002/quote-machine.git

Se déplacer dans le projet :

    $ cd quote-machine 

Installations des dépendances : 

    $ composer install
    $ npm install
    $ yarn install

Compilation webpack :

    $ yarn encore dev


Définir L’URL de la DATABASE dans les variables d'environnement du fichier `.env`  :

> DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

Création base de données :

    $ php bin/console doctrine:database:create
    
Migration :

    $ php bin/console doctrine:migration:migrate
    
Chargement fixture :

    $ php bin/console doctrine:fixture:load

Lancement du serveur de développement :

    $ php bin/console server:start

http://localhost:8000

## Fonctionnalité

### Quote

 1. Création lier a un utilisateur qui pourras la supprimer ou la modifier (Voter).
 2. Vote positif ou négatif, un vote par utilisateur, ce qui trie les quote.
 3. On peut demander une quote aléatoire. 
 4. Pagination de la liste avec sélecteur 10/50/100 pour le nombre de quote afficher par page.
 5. On peut également faire une rechercher dans la liste des quote.

### Catégorie 
 
 1. Les catégorie sont lister on peut voir le slug de chaque catégorie.
 2. Les quote sont relier a des catégorie.
 3. Les catégorie ne sont éditable que par les admin.
 4. On peut afficher les quote par catégorie.
 5. Pagination de la liste avec sélecteur 10/50/100 pour le nombre de quote afficher par page.

### Login

 
|Rôle|Login| Password  |
|--|--|--|
|User|user|user|
|User|user2|user2|
|Admin|admin|admin|

### Commande 

    $ php bin/console app:quote-random -c

**Description:**

  Show a random quote.

**Usage:**
 > app:quote-random [options]

**Options:**

  -c, --category      
  
  _Diplay the category choice_
  
  -h, --help  
  
  _Display this help message_

**Help:**

  This command allows you to show a quote, you can chose between each category or none.

### Test

Exécution des tests :

    $ php bin/phpunit
  
***

_ARESCALDINO
Clément_ 

**LPWIMSI** : 2018 - 2019

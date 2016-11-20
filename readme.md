#TODO Api test

#choix techniques
j'ai choisis d'utiliser php et le framework laravel pour réaliser cette api.
PHP car c'est le language que je maitrise le mieux, ce qui me permet d'écrire cette Api le plus rapidement possible.
Laravel car j'aime ce framework, et il offre `out of the box` toutes les fonctionnalitées nécessaire à la realisation de ce test : un orm, un systeme de test, un routeur...

J'aurais pu utiliser un framework plus léger, mais installer les différentes dépendance utiles à la réalisation de cette api aurait été une perte de temps (orm, framework de test ...)
#Assomption
Dans cette api nous conviendront sur les spécificités suivante : 

* demander une ressource avec un id invalide retourne un tableau vide et non pas une erreur
* créer une ressource ou la modifier retourne la nouvelle instance de celle ci
* modifier une ressource avec un id invalide retourne une erreur sans créer la ressource

#Installation
Pour installer ce projet il vous suffit de créer une base de donnée et de remplacer les champs DB_DATABASE, DB_USER et DB_PASSWD du fichier .env
puis de lancer les commandes suivante : `composer install` puis `php artisan migrate`

Si vous souhaitez lancer le serveur web vous pouvez utiliser la commande : `php artisan serve`
(ce n'est pas obligatoire pour lancer les tests)
#tests
J'utilise une factory avec des transactions pour chaque tests, ceux ci sont donc indépendant les uns des autres, et chaque données creer pour une suite de test est ensuite détruite de la base de donnée.

 pour lancer les test il suffit de lancer la commande `phpunit` a la racine du projet

#endpoints
| method | route | description |
| --- | --- | --- |
| GET | /api/todo/status/{status}| retourne la liste des todos avec le status 1 ou 0 (true ou false) |
| GET | /api/todo/{id} | retourne la todo correspondant a l'id {id}|
| POST | /api/todo/ | crée une nouvelle todo et retourne sa représentation |
| PUT | /api/todo/{id} | édite la todo correspondant a l'id {id} et retourne sa nouvelle représentation
| DELETE | /api/todo/{id} | supprime la todo correspondant a l'id {id}
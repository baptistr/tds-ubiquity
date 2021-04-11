# Explication du Projet

Dans le contexte actuel (crise sanitaire), beaucoup de commerçants ne peuvent plus ouvrir leurs magasins. Ils doivent s'orienter
vers d'autres modes de vente et une des solutions est la vente par internet. C'est pour cela qu'il est important et urgent, en tant
que développeur, de savoir faire un site de vente de produits en utilisant les meilleurs outils afin de créer
un site attractif.

Le framework Ubiquity est le framework php idéal pour pouvoir accomplir cette tâche.
Il fait gagner beaucoup de temps, car le code est mieux réparti entre les différentes pages permettant ainsi d'obtenir
plus de clarté. Il permet aussi d'écrire une fonction avec un nombre de lignes moins important qu'en tant normal, car des
fonctionnalités sont déjà implémentées dans le framework permettant encore une fois d'économiser du temps. Il possède une
architecture MVC, il permet l'utilisation des routeurs etc ...

Pour commencer à travailler sur le projet, il faut installer et utiliser la version 8.0.0 de php ou une version plus récente, il faut aussi évidemment
utiliser git nous permettant de sauvegarder le projet sur un cloud et de pouvoir montrer son avancement. Composer est un outil
de gestion des dépendances pour php, il vous permet de déclarer les bibliothèques dont dépend votre projet et il les gérera. Cela
sera très utile lors de l'installation d'Ubiquity.

Une fois les installations faites ci-dessus, il faut :
 - initialiser un projet Ubiquity (ubiquity new <nom-projet> -a)
 - cloner le projet pour pouvoir l'utiliser sur Github (git clone <repository>)
 - installer Composer dans le dossier <nom-projet> (composer install)
 - démarrer le serveur (ubiquity serve)
 - utiliser l'url "http://127.0.0.1:8090" pour pouvoir visiter l'application.

Dans Ubiquity, nous avons :
 - Les controllers, permettant de gérer les routes et les fonctions qui communiquent avec des éléments extérieurs
 - Les models, permettant d'initaliser des variables, de générer les getters et setters qui seront utilisés principalement dans le controller
 - Les views, permettant d'afficher des éléments préparés par les controllers avec un design.

Nous devons donc faire un site de vente de vehicules. Dans ce site, l'utilisateur peut acheter un ou plusieurs articles et les
mettre dans un ou plusieurs paniers.

les pages :
/MyAuth : page permettant de se connecter
/index : page "dashboard" permettant d'avoir un récapitulatif de nos interventions sur le site
/order : historique des commandes
/store : visualisation du nombre de produits dans les sections / permet de parcourir les sections
/section/x/ : permet de visualiser/d'acheter des produits appartenant à une section
/newBasket : permet de créer un nouveau panier
/basket : permet de voir la liste des paniers
/basketContent/x : permet de voir le contenu des paniers
/product/x/y/ : permet de voir la fiche complète d'un produit
/basket/validate/x : permet de valider le panier
/basket/command : permet de valider la commande


# tds

This README outlines the details of collaborating on this Ubiquity application.
A short introduction of this app could easily go here.

## Prerequisites

You will need the following things properly installed on your computer.

* php ^7.1
* [Git](https://git-scm.com/)
* [Composer](https://getcomposer.org)
* [Ubiquity devtools](https://ubiquity.kobject.net/)

## Installation

* `git clone <repository-url>` this repository
* `cd tds`
* `composer install`

## Running / Development

* `Ubiquity serve`
* Visit your app at [http://127.0.0.1:8090](http://127.0.0.1:8090).

### devtools

Make use of the many generators for code, try `Ubiquity help` for more details

### Optimization for production

Run:
`composer install --optimize --no-dev --classmap-authoritative`

### Deploying

Specify what it takes to deploy your app.

## Further Reading / Useful Links

* [Ubiquity website](https://ubiquity.kobject.net/)
* [Guide](http://micro-framework.readthedocs.io/en/latest/?badge=latest)
* [Doc API](https://api.kobject.net/ubiquity/)
* [Twig documentation](https://twig.symfony.com)
* [Semantic-UI](https://semantic-ui.com)

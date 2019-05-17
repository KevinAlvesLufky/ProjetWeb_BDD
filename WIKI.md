-------------------------------------------------
Auteur : SnowLines

Projet : Site web en PHP avec gestion de Database
-------------------------------------------------

Contenu du dossier :

- Documentation
Le dossier Documentation contient toutes les documentations concernant le projet,
tel que les diagrammes, Les rétrospetives de sprint ainsi que les Tests d'acceptation des consignes.

- Projet_Snow
Le dossier Projet_Snow contient l'architecture du site, son habillage ainsi que les muscles qui lui permettent d'être dynamique.

-----------------------------------------------

Dans le dossier Documentation nous avons les fichiers :

- diagramme_Sequence.vsdx contient le diagramme de séquence du panier

- DiagrammeFlux_Panier.vsdx contient le diagramme de flux du panier

- Réalisation-Tests.doxc contient tous les tests effectués

- SnowLines_Site.vsdx contient le diagramme de flux de tout le site

- Sprint-rétrospective2.docx contient un tableau qui nous aidera pour le sprint rétrospective

-----------------------------------------------

Dans le dossier Projet_Snow nous avons :

Le dossier controler, model, sql, view et le fichier index.php

- Le fichier index.php qui permet de redirection les utilisateurs selon leurs actions

-----------------------------------------------

Dans le dossier controler nous avons :

- Le fichier controler.php qui a toutes les fonctions pour toutes les fonctionnalités du site

-----------------------------------------------

Dans le dossier model nous avons :

- Le fichier cartManager.php qui permet de manipuler les données du cart

- Le fichier dbConnector.php qui permet de se connecter à la base de données pour effectuer les requêtes sql

- Le fichier snowsManager.php qui permet de manipuler les données des snows

- Le fichier userManager.php qui permet de manipuler les données des utilisateurs

-----------------------------------------------

Dans le dossier sql nous avons :

- Le fichier snowsCreateAppliUser.sql c'est une requête sql qui permet de créer l'utilisateur avec lequel on va se connecter à la base de données

- Le fichier sql_createDataBase.sql qui nous permet de créer la base de données

-----------------------------------------------

Dans le dossier view nous avons :

Le dossier content qui contient les images du site + la template du site

- Le fichier aSnow.php contient la vue d'un seule snow

- Le fichier cart.php contient la vue du panier

- le fichier gabarit.php est le fichier qui nous permet de structurer / disposer les vues

- Le fichier home.php contient la vue de la page d'acceuil

- Le fichier login.php contient la vue du login (page de connexion)

- Le fichier register.php contient la vue du register (page d'inscription)

- Le fichier snowLeasingRequest.php contient la vue pour louer les snows

- Le fichier snows.php contient la vue client de tous les snows

- Le fichier snowsSeller.php contient la vue admin de tous les snows

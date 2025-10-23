# SAE-4.Real.01-API

Application web développée dans le cadre d’un projet d’entraînement, visant à concevoir un site pour le CHU de Reims. Le projet est divisé en deux parties : une API (Symfony / API Platform) et une interface front-end (React).

Fonctionnalités principales :

Gestion des utilisateurs : rôles patient, médecin, administrateur.

Gestion de planning : les médecins peuvent gérer leurs créneaux, les patients prendre rendez-vous.

Interface d’administration avec EasyAdmin : possibilité pour les administrateurs de créer, modifier et supprimer toutes les entités.

Utilisation de Symfony Fixtures / Faker pour générer des données de test.

Front-end partiellement développé avec React, consommant l’API pour afficher et manipuler les données.

![](/public/readmeImages/Capture%20d’écran%202025-10-23%20160958.png)
![](/public/readmeImages/Capture%20d’écran%202025-10-23%20161034.png)
![](/public/readmeImages/Capture%20d’écran%202025-10-23%20161128.png)
![](/public/readmeImages/Capture%20d’écran%202025-10-23%20161221.png)

## Auteurs 

* **RAYAUME Hugo** - raya0006
* **PEYRELONGUE Côme** - peyr0018
* **LINDEN Thomas** - lind0013
* **DUPONT Enzo** - dupo0098

## Installation / Configuration

1. Clonez le dépôt distant sur votre machine : 
```
git clone https://iut-info.univ-reims.fr/gitlab/raya0006/sae-4.real.01-api.git
```

2. Lancez la commande Composer d'installation des packages :
```
composer install
```

3. Dupliquez le fichier `.env` en `.env.local` et éditez-le en modifiant la ligne de connexion en :
```
DATABASE_URL="mysql://LOGIN:MOT_DE_PASSE@mysql:3306/NOM_DE_VOTRE_BD?Version=10.2.25-MariaDB&charset=utf8mb4"
```

4. Lancez la génération de la base de données, la création et la migration des fixtures :
```
composer db
```

5. Lancez votre application, disponible à l'adresse [http://127.0.0.1:8000/api](http://127.0.0.1:8000/api) :
```
composer start
```

6. Lancez les différents tests (codeception, PHP CS Fixer) :
```
composer test
```
### Comptes de test

#### Patient
**login** : patient **pass** : test

#### Professionnel
**login** : pro **pass** : test

#### Administrateur
**login** : admin **pass** : test

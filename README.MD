# SAE-4.Real.01-API

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
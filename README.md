# BileMoApi - catalogue en ligne de téléphones haut de gamme

## Présentation du projet
API REST BileMO réalisée avec Symfony 7.

Projet réalisé dans le cadre de la formation _développeur d'application PHP/symfony_ d'OpenClassrooms.

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/f87db5500bb54e7d938d6a0f773c66e2)](https://app.codacy.com/gh/njarach/BileMoApi/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

## Description
**BileMo** est une entreprise offrant toute une sélection de téléphones mobiles haut de gamme en B2B.
Une API REST fournit à toutes les plateformes qui le souhaitent l’accès au catalogue.

Les clients de l’API sont authentifiés via JWT.


## Pré-requis

PHP
**PHP 8.1** ou supérieur

MySQL
**MySQL 8.0** ou supérieur.

Composer
**Composer 2.0** ou supérieur.

## Installation

Cloner le projet

```git clone https://github.com/njarach/BileMoApi.git```

Installer les dépendances

 ```composer install```

_Variables d'environnements_ : Configurer un fichier _.env.local_:
 ```Dotenv
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/bilemoapi?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
```
Pour une mise en production :

```Dotenv
APP_ENV=prod
APP_SECRET=!new32characterskey!
```
Créer les clefs SSL [_aide_](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/2.x/Resources/doc/index.rst#generate-the-ssl-keys)

`symfony console lexik:jwt:generate-keypair`

Configurer les chemins vers les clefs et la passphrase dans le _env.local_ [_aide_](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/2.x/Resources/doc/index.rst#configuration):

```dotenv
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=yourPassphrase
```
Plus d'infos : [Documentation officielle JWT](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/2.x/Resources/doc/index.rst#getting-started)

 Créer la base de données et les tables:

```symfony console doctrine:database:create```

```symfony console doctrine:migrations:migrate``` ou ```symfony console doctrine:schema:update --force```

Charger les données initiales

```symfony console doctrine:fixtures:load```

Lancer le serveur symfony

`symfony server:start`

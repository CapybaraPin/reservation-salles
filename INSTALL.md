# Installation de l'application

Ce guide vous expliquera comment installer et configurer votre application qui utilise Composer pour gérer ses dépendances.

## Prérequis

Avant de commencer, assurez-vous que vous avez installé les éléments suivants :

- **PHP** : La version minimum requise de PHP dépend des dépendances utilisées. Veuillez vous assurer d'avoir une version compatible avec les bibliothèques suivantes.
- **Composer** : Composer est un gestionnaire de dépendances PHP. Vous devez l'avoir installé pour pouvoir installer les bibliothèques nécessaires à votre projet.

  Vous pouvez télécharger Composer ici : [https://getcomposer.org/](https://getcomposer.org/)

- **Extension PDO pour PHP** : L'extension PDO est requise pour interagir avec une base de données. Vérifiez qu'elle est bien installée.

## Étapes d'installation

### 1. Clonez le dépôt

Si vous n'avez pas encore cloné le projet, vous pouvez le faire en exécutant la commande suivante :

```bash
git clone https://github.com/CapybaraPin/reservation-salles/
cd reservation-salles
```

### 2. Installez les dépendances avec Composer

Exécutez la commande suivante pour installer toutes les dépendances listées dans le fichier composer.json :

```bash
composer install 
```

Cela va télécharger et installer toutes les dépendances, notamment :

- Bramus Router (bramus/router): Un micro-router pour PHP.
- Vlucas Dotenv (vlucas/phpdotenv): Une bibliothèque pour gérer les fichiers .env.
- PDO (ext-pdo): L'extension PDO, requise pour la gestion de la base de données.

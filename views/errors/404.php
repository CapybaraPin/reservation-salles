<?php

use services\Config;

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">

    <title>Erreur 404</title>

    <!-- Importation des fichiers CSS pour le style -->
    <link href="<?= Config::get("APP_URL") ?>/assets/css/styles.css" rel="stylesheet"> <!-- CSS principal -->
    <link href="<?= Config::get("APP_URL") ?>/assets/vendor/bootstrap-5.3.3/css/bootstrap.css" rel="stylesheet"> <!-- Framework Bootstrap -->
</head>
<body>
<!-- Conteneur principal pour la page -->
<div class="container">
    <!-- Ligne pour centrer le contenu verticalement et horizontalement -->
    <div class="row justify-content-center align-items-center vh-100">
        <!-- Colonne pour contenir le texte et adapter la largeur -->
        <div class="col-6 col-md-6 col-sm-6 align-self-center">

            <!-- Titre principal indiquant l'erreur 404-->
            <p class="titre-erreur text-center">404</p>

            <!-- Titre secondaire indiquant l'erreur -->
            <p class="titre text-center">Page non trouvée</p>

            <!-- Paragraphe pour expliquer l'erreur et proposer des solutions -->
            <p class="text-center">
                La page que vous tentez d'afficher n'existe pas ou une autre erreur s'est produite.
                Vous pouvez revenir à
                <!-- Lien pour revenir à la page précédente -->
                <a class="text-primary" href="">la page précédente</a>
                ou aller à
                <!-- Lien pour rediriger vers la page d'accueil -->
                <a class="text-primary" href="">la page d'accueil</a>
                .
            </p>
        </div>
    </div>
</div>
</body>
</html>
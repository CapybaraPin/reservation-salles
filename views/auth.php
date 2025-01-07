<?php

use services\Config;

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - Reservit</title>
    <link rel="stylesheet" href="<?= Config::get('APP_NAME'); ?>/assets/css/styles.css">
    <link rel="stylesheet" href="<?= Config::get('APP_NAME'); ?>/assets/vendor/bootstrap-5.3.3/css/bootstrap.css">
</head>
<body>
<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-12 col-md-4 col-sm-4">

            <form action="" method="post">
                <p class="titre text-center">Connectez-vous</p>
                <p class="text-center">Bienvenue ! Entrez vos informations de connexion</p>

                <!-- Message d'erreur -->
                <?php
                    if (isset($message)) {
                        echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
                    }
                ?>

                <!-- Groupe pour le champ "Identifiant" -->
                <div class="form-group">
                    <label class="label-form" for="identifiant">Identifiant</label>
                    <input class="form-control" id="identifiant" name="identifiant" type="text"
                           placeholder="Entrez votre identifiant" required>
                </div>
                <br>
                <!-- Groupe pour le champ "Mot de passe" -->
                <div class="form-group">
                    <label class="label-form" for="mot-de-passe">Mot de passe</label>
                    <input class="form-control" id="mot-de-passe" name="motdepasse" type="password"
                           placeholder="Entrez votre mot de passe" required>
                </div>
                <br>
                <!-- Groupe pour le champ "Se souvenir de moi" -->
                <div class="form-group mb-3">
                    <input class="form-check-input" id="memoriser" name="memoriser" type="checkbox">
                    <label for="memoriser" class="label-form d-inline">Se Souvenir de moi</label>
                </div>

                <!-- Bouton d'envoi -->
                <div class="d-grid">
                    <button class="btn btn-primary" type="submit">Se connecter</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

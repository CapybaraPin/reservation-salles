<?php

use services\Config;

require_once 'utils/tableau.php';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exportation des données</title>

    <?php include 'elements/styles.php'; ?>
</head>
<body>
<div class="container-fluid">
    <?php include 'elements/header.php'; ?>

    <!-- Section principale -->

    <div class="content">
        <!-- Bienvenue et bouton "Ajouter une Activité" -->
        <div class="row">
            <div class="col-12">
                <div class="container">
                    <div class="row mt-5 mb-5">
                        <!-- Message de bienvenue -->
                        <div class="col-12 col-lg-8">
                            <h2>Exporter les données</h2>
                            <p>Téléchargez les données au format CSV.</p>
                        </div>

                        <!-- Bouton pour exporter les données -->
                        <div class="col-12 col-lg-4 text-lg-end">
                            <a class="btn btn-primary" href="<?= Config::get("APP_URL") ?>/exportation/telecharger" target="_blank">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>&emsp; Exporter les données
                            </a>
                        </div>

                        <!-- Statistiques données -->
                        <div class="row mt-5 pl p-3">
                            <div class="col-12 col-lg-4 col-xl-3 offset-lg-2 offset-xl-0">
                                <div class="stat-card">
                                    <i class="fa-regular fa-calendar mb-md-5 mb-2"></i>
                                    <h5>Réservations</h5>
                                    <p>Nombre de réservations :</p>
                                    <h3><?=$nbReservations?></h3>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 col-xl-3">
                                <div class="stat-card">
                                    <i class="fa-solid fa-cube mb-md-5 mb-2"></i>
                                    <h5>Salles</h5>
                                    <p>Nombre de salles :</p>
                                    <h3><?=$nbSalles?></h3>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 col-xl-3 offset-lg-2 offset-xl-0">
                                <div class="stat-card">
                                    <i class="fa-solid fa-users mb-md-5 mb-2"></i>
                                    <h5>Employés</h5>
                                    <p>Nombre d'employés :</p>
                                    <h3><?=$nbEmployes?></h3>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 col-xl-3">
                                <div class="stat-card">
                                    <i class="fa-solid fa-list-ul mb-md-5 mb-2"></i>
                                    <h5>Activités</h5>
                                    <p>Nombre d'activités :</p>
                                    <h3><?=$nbActivites?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php include 'elements/scripts.php'; ?>
</html>
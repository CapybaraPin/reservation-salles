<?php
require_once 'utils/tableau.php';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activites</title>

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
                            <button class="btn btn-primary">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Exporter les données
                            </button>
                        </div>

                        <!-- Statistiques données -->
                        <div class="row mt-4 pl">
                            <div class="col-12 col-md-3">
                                <div class="stat-card">
                                    <i class="fa-regular fa-calendar mb-md-5 mb-2"></i>
                                    <h5>Réservations</h5>
                                    <p>Nombre de réservations :</p>
                                    <h3>150</h3>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="stat-card">
                                    <i class="fa-solid fa-cube mb-md-5 mb-2"></i>
                                    <h5>Salles</h5>
                                    <p>Nombre de salles :</p>
                                    <h3>24</h3>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="stat-card">
                                    <i class="fa-solid fa-users mb-md-5 mb-2"></i>
                                    <h5>Employés</h5>
                                    <p>Nombre d'employés :</p>
                                    <h3>12</h3>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="stat-card">
                                    <i class="fa-solid fa-list-ul mb-md-5 mb-2"></i>
                                    <h5>Activités</h5>
                                    <p>Nombre d'activités :</p>
                                    <h3>5</h3>
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
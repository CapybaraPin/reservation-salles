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
                        <div class="col-12 col-lg-10">
                            <h2>Gestion des activités</h2>
                            <p>Créer et gérer vos activités depuis le tableau ci-dessous.</p>
                        </div>
                    </div>

                    <!-- Section filtres et recherche -->
                    <div class="row mt-4 mb-4">
                        <div class="col-12 col-lg-9">
                            <!-- Filtres pour les Salles -->
                            <div class="btn border border-1 shadow-sm me-2">
                                Salle : "picasso" <i class="fa-solid fa-xmark text-primary ps-2"></i>
                            </div>
                            <div class="btn border border-1 shadow-sm me-2">
                                <i class="fa-solid fa-filter"></i> Plus de filtres
                            </div>
                        </div>
                        <!-- Champ de recherche -->
                        <div class="col-12 col-lg-3 mt-lg-0 mt-2">
                            <div class="input-group">
                                <i class="fa-solid input-group-text d-flex">&#xf002;</i>
                                <input class="form-control" type="text" name="recherche" placeholder="Recherche">
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des Activités -->
                    <?php
                        echo genererTableau($activites, $colonnes, $titre, $nbActivites);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php include 'elements/scripts.php'; ?>
</html>
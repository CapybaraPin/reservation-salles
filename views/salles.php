<?php
require_once 'utils/tableau.php';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Salles</title>

    <?php include 'elements/styles.php'; ?>
</head>
<body>
<div class="container-fluid">
    <?php include 'elements/header.php'; ?>

    <!-- Section principale -->

    <div class="content">
        <!-- Bienvenue et bouton "Ajouter une salle" -->
        <div class="row">
            <div class="col-12">
                <div class="container">
                    <div class="row mt-5 mb-5">
                        <!-- Message de bienvenue -->
                        <div class="col-12 col-lg-10">
                            <h2>Gestion des salles</h2>
                            <p>Créer et gérer vos salles depuis le tableau ci-dessous.</p>
                        </div>
                        <!-- Bouton pour ajouter une salle -->
                        <div class="col-12 col-lg-2 text-lg-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterSalle">
                                <i class="fa-solid fa-plus"></i> Ajouter une salle
                            </button>
                        </div>
                    </div>

                    <!-- Section filtres et recherche -->
                    <div class="row mt-4 mb-4">
                        <div class="col-12 col-lg-9">
                            <!-- Filtres pour les salles -->
                            <div class="btn border border-1 shadow-sm me-2">
                                salle : "picasso" <i class="fa-solid fa-xmark text-primary ps-2"></i>
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

                    <!-- Tableau des salles -->
                    <?php
                        echo genererTableau($salles, $colonnes, $titre, $nbSalles, $actions, $page, $pageMax);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'modals/ajouterSalle.php'; ?>

</body>

<?php
    include 'elements/scripts.php'; ?>
</html>
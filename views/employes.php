<?php
require_once 'utils/tableau.php';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employés</title>

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
                            <h2>Gestion des employés</h2>
                            <p>Créer et gérer vos employés depuis le tableau ci-dessous.</p>
                        </div>
                        <!-- Bouton pour ajouter une salle -->
                        <?php if ($_SESSION['userRole'] == '1') { ?>
                        <div class="col-12 col-lg-2 text-lg-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterEmployee">
                                <i class="fa-solid fa-plus"></i>&emsp; Ajouter un employé
                            </button>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <?php if (isset($erreur)) { ?>
                            <div class="alert alert-danger mt-3" role="alert">
                                <?= $erreur ?>
                            </div>
                        <?php }
                            if (isset($success)) { ?>
                                <div class="alert alert-success mt-3" role="alert">
                                    <?= $success ?>
                                </div>
                        <?php } ?>
                        <?php if (isset($alerte)) { ?>
                            <div class="alert alert-warning mt-3" role="alert">
                                <?= $alerte ?>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Section filtres et recherche -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <?php include 'elements/filtres.php'; ?>
                        </div>
                    </div>


                    <!-- Tableau des employés -->
                    <?= genererTableau($employes, $colonnes, $titre, $nbEmployes, $actions, $page, $pageMax, $filtres); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'modals/ajouterEmploye.php';?>
<?php include 'modals/supprimerEmploye.php';?>
<?php include 'elements/scripts.php'; ?>
</body>
</html>
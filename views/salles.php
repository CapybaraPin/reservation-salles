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

                    <?php if (!empty($success)) { ?>
                        <div class="alert alert-success">
                            <?= $success ?>
                        </div>
                    <?php } ?>

                    <?php if (!empty($erreurs)) { ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($erreurs as $champ => $message) { ?>
                                    <li><?= $message ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>

                    <!-- Section filtres et recherche -->
                    <?php include 'elements/filtres.php'; ?>

                    <!-- Tableau des salles -->
                    <?= genererTableau($salles, $colonnes, $titre, $nbSalles, $actions, $page, $pageMax, $filtres); ?>
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
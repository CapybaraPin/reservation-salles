<?php
require_once 'utils/tableau.php';
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réservations</title>

    <?php include 'elements/styles.php'; ?>
</head>
<body>
<div class="container-fluid">
    <?php include 'elements/header.php'; ?>

    <!-- Section principale -->

    <div class="content">
        <!-- Bienvenue et bouton "Ajouter une réservation" -->
        <div class="row">
            <div class="col-12">
                <div class="container">
                    <div class="row mt-5 mb-5">
                        <!-- Message de bienvenue -->
                        <div class="col-12 col-lg-8">
                            <h2>Gestion des réservations</h2>
                            <p>Créer et gérer vos réservations depuis le tableau ci-dessous.</p>
                        </div>
                        <!-- Bouton pour ajouter une réservation -->
                        <div class="col-12 col-lg-4 text-lg-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterReservation">
                                <i class="fa-solid fa-plus"></i>&emsp; Ajouter une réservation
                            </button>
                        </div>
                    </div>

                    <?php if (!empty($success)) { ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php } ?>
                    

                    <?php if (!empty($erreurs)) { ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($erreurs as $champ => $message) : ?>
                                    <li><strong><?= htmlspecialchars($champ) ?> :</strong> <?= htmlspecialchars($message) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php } ?>
                  
                    <?php if (isset($erreur)) { ?>
                        <div class="alert alert-danger mt-3" role="alert">
                            <?= $erreur ?>
                        </div>
                    <?php } ?>
                  
                    <?php if (isset($alerte)) { ?>
                        <div class="alert alert-warning mt-3" role="alert">
                            <?= $alerte ?>
                        </div>
                    <?php } ?>
             

                    <!-- Section filtres et recherche -->
                    <?php include 'elements/filtres.php'; ?>
                    <!-- Tableau des réservations -->
                    <?php
                        echo genererTableau($reservations, $colonnes, $titre, $nbReservations, $actions, $page, $pageMax, $filtres);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'modals/ajouterReservation.php'; ?>

<?php include 'modals/supprimerReservation.php';?>

</body>
<?php include 'elements/scripts.php'; ?>
</html>
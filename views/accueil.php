<?php
require_once 'utils/tableau.php';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes réservations</title>

    <?php include 'elements/styles.php'; ?>
</head>
<body>
<div class="container-fluid">
    <?php include 'elements/header.php'; ?>

    <div class="content">
        <!-- Bienvenue et bouton "Ajouter une Activité" -->
        <div class="row">
            <div class="col-12">
                <div class="container">
                    <div class="row mt-5 mb-5">
                        <!-- Message de bienvenue -->
                        <div class="col-12 col-lg-9">
                            <h2>Bienvenue à vous, <?= $_SESSION['userPrenom'] ?> !</h2>
                            <p>Créer et gérer vos réservations en toute simplicité.</p>
                        </div>
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


                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <!-- Bienvenue et bouton "Ajouter une réservation" -->
        <div class="row">
            <div class="col-12">
                <div class="container">
                    <?php
                        echo genererTableau($reservations, $colonnes, $titre, $nbReservations, $actions, $page, $pageMax);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'modals/supprimerReservation.php'; ?>


</body>
<?php include 'elements/scripts.php'; ?>
</html>
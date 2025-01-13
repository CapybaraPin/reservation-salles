<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $salle["NOM_SALLE"] ?> - Consultation</title>

    <?php include 'elements/styles.php'; ?>
</head>
<body>
<div class="container-fluid">
    <?php include 'elements/header.php'; ?>
    <div class="content">
        <div class="row">
            <div class="col-12">
                <div class="container">
                    <!-- Section de bienvenue et actions -->
                    <div class="row mt-5 mb-4">
                        <div class="col-12 col-lg-8">
                            <h2 class="">Consultation de la salle</h2>
                            <p class="">Visualisez les informations de la salle <b>« <?= $salle["NOM_SALLE"] ?> »</b></p>
                        </div>

                        <!-- Boutons pour supprimer et modifier -->
                        <div class="col-12 col-lg-4 text-lg-end">
                            <a href="/salle/<?= $salle["ID_SALLE"] ?>/edit" class="btn btn-primary btn-consultation">
                                <i class="fa-solid fa-pencil"></i>&emsp; Modifier
                            </a>
                            <button class="btn btn-danger btn-consultation" <?php if ($nbReservations > 0){ echo 'disabled' ; } ?> data-bs-toggle="modal" data-bs-target="#modal_supprimer_salle">
                                <i class="fa-solid fa-trash"></i>&emsp; Supprimer
                            </button>
                        </div>
                    </div>

                    <!-- Informations sur la salle -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h3 class="card-title">Informations de la salle</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <th scope="row" width="300px">Identifiant</th>
                                            <td><?= $salle["ID_SALLE"] ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Nom</th>
                                            <td><?= $salle["NOM_SALLE"] ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Capacité</th>
                                            <td><?= $salle["CAPACITE"] ?> personnes</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Vidéo Projecteur</th>
                                            <td><?= $salle["VIDEO_PROJECTEUR"] == "1" ? 'Oui' : 'Non' ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Ecran XXL</th>
                                            <td><?= $salle["ECRAN_XXL"] == "1" ? 'Oui' : 'Non' ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations sur les ordinateurs -->
                    <?php if ($salle["ID_ORDINATEUR"] != 0) : ?>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-secondary text-white">
                                        <h3 class="card-title">Ordinateurs de la salle</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <th scope="row" width="300px">Nombre d'ordinateurs</th>
                                                <td><?= $ordinateurs["NB_ORDINATEUR"] ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Type d'ordinateurs</th>
                                                <td><?= $ordinateurs["DESIGNATION_TYPE"] ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Imprimante</th>
                                                <td><?= $ordinateurs["IMPRIMANTE"] == "1" ? 'Oui' : 'Non' ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Logiciels</th>
                                                <td>
                                                    <?php
                                                    if (empty($logiciels)) {
                                                        echo "Aucun logiciel installé.";
                                                    } else {
                                                        foreach ($logiciels as $logiciel) {
                                                            echo '<span class="badge bg-primary">' . $logiciel["NOM_LOGICIEL"] . '</span> ';
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'modals/supprimerSalle.php'; ?>

<?php
include 'elements/scripts.php'; ?>
</body>
</html>

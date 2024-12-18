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
                            <button class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Ajouter une réservation
                            </button>
                        </div>
                    </div>

                    <!-- Section filtres et recherche -->
                    <div class="row mt-4 mb-4">
                        <div class="col-12 col-lg-9">
                            <!-- Filtres pour les réservations -->
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

                    <!-- Tableau des réservations -->
                    <div class="row">
                        <div class="col-12">
                            <div class="border border-1 rounded rounded-4 shadow-sm">
                                <!-- Titre du tableau -->
                                <p class="p-3 pb-0 fw-bold">Mes réservations
                                    <button class="btn disabled badge text-primary text-wrap">
                                        <?= $nbReservations === 0 ? "Aucune réservation" : $nbReservations . " réservations"?>
                                    </button>
                                </p>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="table-light">
                                        <!-- En-tête du tableau -->
                                        <tr>
                                            <th><input type="checkbox" name="" id="" class="ms-2 form-check-input"></th>
                                            <th>Identifiant</th>
                                            <th>Date de début</th>
                                            <th>Date de fin</th>
                                            <th>Description</th>
                                            <th>Salle</th>
                                            <th>Activité</th>
                                            <th>Employé</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <!-- Première ligne de données (exemple) -->
                                        <?php foreach ($reservations as $reservation) {
                                            $dateDebut = date_create($reservation["DATE_DEBUT"]);
                                            $dateDebutFormatted = date_format($dateDebut, "d/m/Y H\hi");
                                            // todo for jules vialas : normaliser l'identificateur en français
                                            $dateFin = date_create($reservation["DATE_FIN"]);
                                            $dateFinFormatted = date_format($dateFin, "d/m/Y H\hi");
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" name="" id="" class="ms-2 form-check-input"></td>
                                                <td><?= $reservation["IDENTIFIANT_RESERVATION"] ?></td>
                                                <td><?= $dateDebutFormatted ?></td>
                                                <td><?= $dateFinFormatted ?></td>
                                                <td><?= $reservation["DESCRIPTION"] ?></td>
                                                <td><?= $reservation["NOM_SALLE"] ?></td>
                                                <td><?= $reservation["TYPE_ACTIVITE"] ?></td>
                                                <td><?= $reservation["PRENOM_EMPLOYE"] . " " . $reservation["NOM_EMPLOYE"] ?></td>
                                                <!-- Boutons d'action -->
                                                <td>
                                                    <button class="btn btn-nav" title="Plus d'informations">
                                                        <i class="fa-solid fa-circle-info"></i>
                                                    </button>
                                                    <button class="btn" title="Modifier">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </button>
                                                    <button class="btn btn-nav" title="Supprimer">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="container-fluid">
                                    <div class="row">
                                        <!-- Boutons de navigation -->
                                        <div class="col-3 d-lg-none d-block">
                                            <a href="?page=<?= $page - 1 ?>" class="btn btn-outline-dark d-lg-none">
                                                <i class="fa-solid fa-arrow-left"></i>
                                            </a>
                                        </div>
                                        <div class="col-3 d-lg-block d-none">
                                            <a href="?page=<?= $page - 1 ?>" class="btn btn-outline-dark">
                                                <i class="fa-solid fa-arrow-left"></i> Précédent
                                            </a>
                                        </div>
                                        <div class="col-6 d-flex justify-content-center">
                                            <nav class="text-center">
                                                <ul class="pagination-page">
                                                    <!-- Liens de pagination -->
                                                    <?php
                                                    // TODO : gerer le cas ou le nombre de pages est superieur a 5 par exemple
                                                    // TODO : fix l'alignement de la pagination format mobile
                                                    for ($i = 1; $i <= $pageMax; $i++) {
                                                        $active = $i == $page ? "active d-md-flex" : "d-none d-md-flex";
                                                        echo "<li class='pagination-item  $active'><a class='page-link' href='?page=$i'>$i</a></li>";
                                                    }
                                                    ?>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                        <div class="col-3 text-end d-lg-block d-none">
                                            <a href="?page=<?= $page + 1 ?>" class="btn btn-outline-dark">Suivant
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        </div>
                                        <div class="col-3 text-end d-lg-none d-block">
                                            <a href="?page=<?= $page + 1 ?>" class="btn btn-outline-dark d-lg-none">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Fin du tableau -->
                    </div> <!-- Fin de la ligne principale -->
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php include 'elements/scripts.php'; ?>
</html>
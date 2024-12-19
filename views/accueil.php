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
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <!-- Bienvenue et bouton "Ajouter une réservation" -->
        <div class="row">
            <div class="col-12">
                <div class="container">
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
                                            if ($reservation["PRENOM_EMPLOYE"] === $_SESSION['userPrenom'] ) {
                                                $dateDebut = date_create($reservation["DATE_DEBUT"]);
                                                $dareDebutFormate = date_format($dateDebut, "d/m/Y H\hi");
                                                $dateFin = date_create($reservation["DATE_FIN"]);
                                                $dareFinFormate = date_format($dateFin, "d/m/Y H\hi");
                                                ?>
                                            <tr>
                                                <td><input type="checkbox" name="" id="" class="ms-2 form-check-input"></td>
                                                <td><?= $reservation["IDENTIFIANT_RESERVATION"] ?></td>
                                                <td><?= $dareDebutFormate ?></td>
                                                <td><?= $dareFinFormate ?></td>
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
                                        <?php }} ?>
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
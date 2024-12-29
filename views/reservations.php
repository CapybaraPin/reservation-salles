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
                                <i class="fa-solid fa-plus"></i> Ajouter une réservation
                            </button>
                        </div>
                    </div>

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

<!-- Modal pour ajouter une réservation -->
<div class="modal fade" id="ajouterReservation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <form method="post" action="">
                        <!-- Titre du modal -->
                        <div class="row">
                            <h1 class="modal-title fs-5 mt-2 titre text-start" id="exampleModalLabel">Ajout d'une réservation</h1>
                            <div class="row">
                                <p class="mt-3">Vous ajoutez une réservation à votre nom, suivez le formulaire d’ajout de réservation.</p>
                            </div>
                        </div>
                        <!-- Contenu dynamique -->
                        <div id="content-section">
                            <!-- Étape 1 : Champ Crénaux et Salle -->
                            <div class="row">
                                <!-- Champ Crénaux -->
                                <div class="form-group mb-1">
                                    <label class="label-form" for="dateDebut">Crénaux</label>
                                    <input class="form-control" id="dateDebut" name="dateDebut" type="date" placeholder="Date de début" required>
                                    <input class="form-control" id="dateFin" name="dateFin" type="date" placeholder="Date de fin" required>
                                </div>
                                <!-- Champ Salle -->
                                <div class="form-group mt-1 mb-1">
                                    <label class="label-form" for="salle">Salle</label>
                                    <select class="form-select" id="salle" name="salle">
                                        <option value="0">Sélectionner une salle</option>
                                        <option value="1">Salle 1</option>
                                        <option value="2">Salle 2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Boutons -->
                        <div class="row mt-3 mb-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-dark w-100" data-bs-dismiss="modal">
                                    Annuler
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" id="next-button" class="btn btn-primary w-100">Suivant</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fin de la modal d'ajout réservation -->

</body>
<?php include 'elements/scripts.php'; ?>
</html>
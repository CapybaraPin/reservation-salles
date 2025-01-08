<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consultation de la réservation</title>
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
                            <h2 class="">Consultation de la réservation</h2>
                            <p class="">Visualisez les informations de la réservation <?= $reservation["IDENTIFIANT_RESERVATION"] ?> du <b><?= $reservation["dateDebut"] ?></b> au <b><?= $reservation["dateFin"] ?></b></p>
                        </div>

                        <!-- Boutons pour supprimer et modifier -->
                        <div class="col-12 col-lg-4 text-lg-end">
                            <a href="" class="btn btn-primary" >
                                <i class="fa-solid fa-pencil"></i> Modifier
                            </a>
                            <button class="btn btn-danger" <?php if ($_SESSION['userPrenom'] != $reservation["PRENOM_EMPLOYE"]){ echo 'disabled' ; } ?> data-bs-toggle="modal" data-bs-target="#supprimerSalle">
                                <i class="fa-solid fa-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>

                    <!-- Informations sur la réservation -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h3 class="card-title">Informations de la réservation</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <th scope="row" width="300px">Identifiant</th>
                                            <td><?= $reservation["IDENTIFIANT_RESERVATION"] ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Date de début </th>
                                            <td><?= $reservation["dateDebut"] ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Date de fin </th>
                                            <td><?= $reservation["dateFin"] ?></td>
                                        </tr>
                                        <tr>
                                        <tr>
                                        <tr>
                                            <?php if ($reservation["ACTIVITE"] == "formation") : ?>
                                                <th scope="row">Sujet de la formation</th>
                                                <td><?= $reservation["description"] ?></td>
                                            <?php elseif ($reservation["ACTIVITE"] == "prêt") : ?>
                                                <th scope="row">Sujet du prêt</th>
                                                <td><?= $reservation["description"] ?></td>
                                            <?php elseif ($reservation["ACTIVITE"] == "location") : ?>
                                                <th scope="row">Sujet de la location</th>
                                                <td><?= $reservation["description"] ?></td>
                                            <?php elseif ($reservation["ACTIVITE"] == "réunion") : ?>
                                                <th scope="row">Sujet de la réunion</th>
                                                <td><?= $reservation["description"] ?></td>
                                            <?php elseif ($reservation["ACTIVITE"] == "entretien de la salle") : ?>
                                                <th scope="row">Raison de l'entretien</th>
                                                <td><?= $reservation["description"] ?></td>
                                            <?php else : ?>
                                                <th scope="row">Description</th>
                                                <td><?= $reservation["description"] ?></td>
                                            <?php endif; ?>
                                        </tr>

                                        </tr>

                                        </tr>
                                        <tr>
                                            <th scope="row">Salle</th>
                                            <td><?= $reservation["NOM_SALLE"] ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Activité</th>
                                            <td><?= $reservation["ACTIVITE"] ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Employé</th>
                                            <td><?= $reservation["PRENOM_EMPLOYE"] ?> <?= $reservation["NOM_EMPLOYE"] ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations sur le formateur -->
                    <?php if ($reservation["ACTIVITE"] == "formation") : ?>
                    <div class="row mt-4 mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h3 class="card-title">Informations du formateur</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <th scope="row" width="300px">Identifiant</th>
                                            <td><?= $reservation["FORMATEUR"] ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Nom</th>
                                            <td><?= $formateur["nom"] ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Prenom</th>
                                            <td><?= $formateur["prenom"] ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Numéro de téléphone</th>
                                            <td><?= $formateur["telephone"] ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($reservation["ACTIVITE"] == "prêt" || $reservation["ACTIVITE"] == "location" ) : ?>
                    <div class="row mt-4 mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h3 class="card-title">Informations de l'organisme et l'interlocuteur </h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <th scope="row" width="300px"> Identifiant organisme</th>
                                            <td><?= $organisation["identifiant"] ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row" width="300px"> Identifiant organisme</th>
                                            <td><?= $organisation["nomOrganisme"] ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row" width="300px">Identifiant</th>
                                            <td><?= $organisation["idInterlocuteur"] ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Nom</th>
                                            <td><?= $formateur["nom"] ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Prenom</th>
                                            <td><?= $formateur["prenom"] ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Numéro de téléphone</th>
                                            <td><?= $formateur["telephone"] ?></td>
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
</body>
</html>


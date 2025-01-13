<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modification de la réservation</title>
    <?php include 'elements/styles.php'; ?>
</head>
<body>
<div class="container-fluid">
    <?php include 'elements/header.php'; ?>
    <div class="content">

        <div class="container">
            <div class="row mt-5 mb-4">
                <div class="col-12 col-lg-8">
                    <h2 class="">Modification de la réservation</h2>
                    <p class="">Réservation de <b><?= $reservation["PRENOM_EMPLOYE"] ?> <?= strtoupper($reservation["NOM_EMPLOYE"]) ?></b> le <b><?= date("d/m/Y H\hi", strtotime($reservation["DATE_DEBUT"])) ?></b> au <b><?= date("d/m/Y H\hi", strtotime($reservation["DATE_FIN"])) ?></b></p>
                </div>

                <!-- Boutons pour revenir ou soumettre -->
                <div class="col-12 col-lg-4 text-lg-end">
                    <a href="/reservations/<?= $reservation['IDENTIFIANT_RESERVATION'] ?>/view" class="btn btn-secondary btn-consultation">
                        <i class="fa-solid fa-info-circle"></i>&emsp; Informations
                    </a>

                    <button type="submit" class="btn btn-primary btn-consultation" name="modifierReservation">
                        <i class="fa-solid fa-save"></i>&emsp; Sauvegarder
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h3 class="card-title">Informations sur la réservation</h3>
                        </div>
                        <div class="card-body">
                            <!-- Identifiant réservation -->
                            <div class="mb-3">
                                <label for="identifiantReservation" class="form-label">Identifiant de la réservation</label>
                                <input type="text" id="identifiantReservation" name="identifiantReservation" class="form-control" value="<?= $reservation["IDENTIFIANT_RESERVATION"] ?>" disabled>
                            </div>

                            <!-- Employé -->
                            <div class="mb-3">
                                <label for="nomEmploye" class="form-label">Nom de l'employé réservataire</label>
                                <input type="text" id="nomEmploye" name="nomEmploye" class="form-control" value="<?= $reservation["PRENOM_EMPLOYE"] ?> <?= strtoupper($reservation["NOM_EMPLOYE"]) ?>" disabled>
                            </div>

                            <!-- Date et heure de début -->
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label for="dateDebut" class="form-label">Date de début</label>
                                    <input type="date" id="dateDebut" name="dateDebut" class="form-control" value="<?= date("Y-m-d", strtotime($reservation["DATE_DEBUT"])) ?>" required>
                                </div>
                                <div class="col-2">
                                    <label for="heureDebut" class="form-label">Heure de début</label>
                                    <input type="time" id="heureDebut" name="heureDebut" class="form-control" value="<?= date('H:i', strtotime($reservation["DATE_DEBUT"])) ?>" required>
                                </div>
                            </div>

                            <!-- Date de fin -->
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label for="dateFin" class="form-label">Date de fin</label>
                                    <input type="date" id="dateFin" name="dateFin" class="form-control" value="<?= date("Y-m-d", strtotime($reservation["DATE_FIN"])) ?>" required>
                                </div>
                                <div class="col-2">
                                    <label for="heureFin" class="form-label">Heure de fin</label>
                                    <input type="time" id="heureFin" name="heureFin" class="form-control" value="<?= date('H:i', strtotime($reservation["DATE_FIN"])) ?>" required>
                                </div>
                            </div>

                            <!-- Type d'activité -->
                            <div class="mb-3">
                                <label for="typeActivite" class="form-label">Type d'activité</label>
                                <select id="typeActivite" name="typeActivite" class="form-select" required>
                                    <option value="0">Sélectionner le type d'activité</option>
                                    <?php foreach ($activites as $activite) { ?>
                                        <option value="<?= $activite['IDENTIFIANT_ACTIVITE'] ?>" <?php if ($activite['IDENTIFIANT_ACTIVITE'] == $reservation["IDENTIFIANT_ACTIVITE"]) { echo "selected"; } ?>>
                                            <?= $activite['TYPE_ACTIVITE'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!-- Salle -->
                            <div class="mb-3">
                                <label for="salle" class="form-label">Salle</label>
                                <select id="salle" name="salle" class="form-select" required>
                                    <?php foreach ($salles as $salle) { ?>
                                        <option value="<?= $salle['ID_SALLE'] ?>" <?php if ($salle['ID_SALLE'] == $reservation["IDENTIFIANT_SALLE"]) { echo "selected"; } ?>>
                                            <?= $salle['NOM_SALLE'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- Informations complémentaires (affichage conditionnel en PHP) -->
                    <div class="card mt-4">
                        <div class="card-header bg-secondary text-white">
                            <h3 class="card-title">Informations complémentaires</h3>
                        </div>
                        <div class="card-body">
                            <!-- Si c'est une Réunion-->
                            <div id="typeDescription" style="<?php if ($reservation["IDENTIFIANT_ACTIVITE"] != 1 OR $reservation["IDENTIFIANT_ACTIVITE"] != 3 OR $reservation["IDENTIFIANT_ACTIVITE"] != 6) { echo "display:none;"; } ?>">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description de l'activité</label>
                                    <input type="text" id="description" name="description" class="form-control" placeholder="Description" value="<?= $reservation['DESCRIPTION'] ?? '' ?>">
                                </div>
                            </div>

                            <!-- Si c'est une Formation -->
                            <div id="typeFormation" style="<?php if ($reservation["IDENTIFIANT_ACTIVITE"] != 2) { echo "display:none;"; } ?>">
                                <div class="mb-3">
                                    <label for="nomIntervenant" class="form-label">Nom du formateur</label>
                                    <input type="text" id="nomIntervenant" name="nomIntervenant" class="form-control" placeholder="Nom du formateur" value="<?= $reservation['NOM_FORMATEUR'] ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="prenomIntervenant" class="form-label">Prénom du formateur</label>
                                    <input type="text" id="prenomIntervenant" name="prenomIntervenant" class="form-control" placeholder="Prénom du formateur" value="<?= $reservation['PRENOM_FORMATEUR'] ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="telephoneIntervenant" class="form-label">N° de téléphone du formateur</label>
                                    <input type="tel" id="telephoneIntervenant" name="telephoneIntervenant" class="form-control" placeholder="N° de téléphone" value="<?= $reservation['PRENOM_FORMATEUR'] ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="sujetFormation" class="form-label">Sujet de la formation</label>
                                    <input type="text" id="sujetFormation" name="sujetFormation" class="form-control" placeholder="Sujet de la formation" value="<?= $reservation['SUJET_FORMATION'] ?? '' ?>">
                                </div>
                            </div>


                            <!-- Si c'est une Location ou Prêt -->
                            <div id="typeLocation" style="<?php if ($reservation["IDENTIFIANT_ACTIVITE"] != 4 OR $reservation["IDENTIFIANT_ACTIVITE"] != 5) { echo "display:none;"; } ?>">
                                <div class="mb-3">
                                    <label for="nomOrganisme" class="form-label">Nom de l'organisme</label>
                                    <select id="nomOrganisme" name="salle" class="form-select" required>
                                        <option>Sélectionnez un organisme</option>
                                        <?php foreach ($organismes as $organisme) { ?>
                                            <option value="<?= $organisme['IDENTIFIANT_ORGANISME'] ?>">
                                                <?= $organisme['NOM_ORGANISME'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <div class="form-text"><button type="button" data-bs-toggle="modal" data-bs-target="#ajouterOrganisme" class="btn-link">Vous ne trouvez pas votre organisme? Ajoutez le</button></div>
                                </div>
                                <div class="mb-3">
                                    <label for="sujetIntervention" class="form-label">Sujet de la location/prêt</label>
                                    <input type="text" id="sujetIntervention" name="sujetIntervention" class="form-control" placeholder="Sujet de la location/prêt" value="<?= $reservation['SUJET_FORMATION'] ?? '' ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'modals/ajouterOrganisme.php';
      include 'elements/scripts.php'; ?>

<script>
    $( '#nomOrganisme' ).select2( {
        theme: 'bootstrap-5'
    } );
</script>
</body>
</html>

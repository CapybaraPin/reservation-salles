<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier la salle <?= $salle["NOM_SALLE"] ?> - Consultation</title>

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
                            <h2 class="">Modification de la salle</h2>
                            <p class="">Modifiez les informations de la salle <b>« <?= $salle["NOM_SALLE"] ?> »</b></p>
                        </div>

                        <!-- Boutons pour revenir ou soumettre -->
                        <div class="col-12 col-lg-4 text-lg-end">
                            <a href="/salle/<?= $salle['ID_SALLE'] ?>/view" class="btn btn-secondary">
                                <i class="fa-solid fa-info-circle"></i> Informations
                            </a>
                        </div>
                    </div>

                    <!-- Formulaire de modification de la salle et des ordinateurs -->
                    <form method="POST" action="" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-secondary text-white">
                                        <h3 class="card-title">Formulaire de modification</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="container">
                                            <!-- SECTION 1: Informations sur la salle -->

                                            <h4>Informations sur la salle</h4>

                                            <!-- Nom de la salle -->
                                            <div class="row">
                                                <div class="form-group mb-3">
                                                    <label class="label-form" for="nom">Nom de la salle</label>
                                                    <input class="form-control" id="nom" name="nom" type="text" placeholder="Entrez le nom de la salle" value="<?= htmlspecialchars($salle['NOM_SALLE']) ?>" required>
                                                    <div class="invalid-feedback">
                                                        Veuillez entrer un nom pour la salle.
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Capacité de la salle -->
                                            <div class="row">
                                                <div class="form-group mb-3">
                                                    <label class="label-form" for="capacite">Capacité de la salle</label>
                                                    <input class="form-control" id="capacite" name="capacite" type="number" placeholder="Entrez la capacité de la salle" min="1" value="<?= htmlspecialchars($salle['CAPACITE']) ?>" required>
                                                    <div class="invalid-feedback">
                                                        Veuillez entrer une capacité valide.
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Vidéo projecteur -->
                                            <div class="row">
                                                <div class="form-group mt-2 mb-2">
                                                    <div class="form-check">
                                                        <input name="videoProjecteur" class="form-check-input" type="checkbox" value="1" id="videoProjecteur" <?= $salle['VIDEO_PROJECTEUR'] == '1' ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="videoProjecteur">
                                                            Possède un vidéo projecteur ?
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Écran XXL -->
                                            <div class="row">
                                                <div class="form-group mt-2 mb-2">
                                                    <div class="form-check">
                                                        <input name="ecranXXL" class="form-check-input" type="checkbox" value="1" id="ecranXXL" <?= $salle['ECRAN_XXL'] == '1' ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="ecranXXL">
                                                            Possède un écran XXL ?
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- SECTION 2: Informations sur les ordinateurs -->
                                            <?php if($salle["ID_ORDINATEUR"] != 0 ){ ?>

                                            <hr>

                                            <h4>Informations sur les ordinateurs</h4>

                                            <!-- Nombre d'ordinateurs -->
                                            <div class="row">
                                                <div class="form-group mb-3">
                                                    <label class="label-form" for="nbOrdinateurs">Nombre d'ordinateurs dans la salle</label>
                                                    <input class="form-control" id="nbOrdinateurs" name="nbOrdinateurs" type="number" placeholder="Entrez le nombre d'ordinateurs" min="0" value="<?= isset($ordinateurs["NB_ORDINATEUR"]) ? htmlspecialchars($ordinateurs["NB_ORDINATEUR"]) : '' ?>" required>
                                                    <div class="invalid-feedback">
                                                        Veuillez entrer le nombre d'ordinateurs.
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Logiciels installés -->
                                            <div class="row">
                                                <div class="form-group mb-3">
                                                    <label class="label-form" for="logiciels">Logiciels présents sur les ordinateurs</label>
                                                    <select class="form-select" id="logiciels" name="logiciels[]" multiple aria-label="multiple select example">
                                                        <option value="-1" selected>Sélectionnez le(s) logiciel(s)</option>
                                                        <?php foreach ($logiciels as $logiciel) : ?>
                                                            <option value="<?= $logiciel['identifiant'] ?>" <?= in_array($logiciel['identifiant'], $logicielsInstalles) ? 'selected' : '' ?>><?= $logiciel['nom'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Imprimante -->
                                            <div class="row">
                                                <div class="form-group mt-2 mb-2">
                                                    <div class="form-check">
                                                        <input name="imprimante" class="form-check-input" type="checkbox" value="1" id="imprimante" <?= isset($ordinateurs['IMPRIMANTE']) && $ordinateurs['IMPRIMANTE'] == '1' ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="imprimante">
                                                            Possède une imprimante ?
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Type d'ordinateurs -->
                                            <div class="row">
                                                <div class="form-group mb-3">
                                                    <label class="label-form" for="typeOrdinateur">Type d'ordinateurs</label>
                                                    <select class="form-select" id="typeOrdinateur" name="typeOrdinateur" required>
                                                        <option value="">Sélectionnez un type d'ordinateur</option>
                                                        <?php foreach ($typesOrdinateur as $type) : ?>
                                                            <option value="<?= $type['identifiant'] ?>" <?= isset($salle['ID_ORDINATEUR']) && $salle['ID_ORDINATEUR'] == $type['identifiant'] ? 'selected' : '' ?>><?= $type['type'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Veuillez sélectionner un type d'ordinateur.
                                                    </div>
                                                </div>
                                            </div>

                                            <?php } ?>

                                            <!-- Boutons de soumission -->
                                            <div class="row mt-3 mb-2">
                                                <div class="col-6">
                                                    <a href="/salle/<?= $salle["ID_SALLE"] ?>/view" class="btn btn-outline-dark w-100" >
                                                        Annuler
                                                    </a>
                                                </div>
                                                <div class="col-6">
                                                    <button type="submit" class="btn btn-primary w-100" name="modifierSalleOrdinateurs">
                                                        <i class="fa-solid fa-save"></i> Sauvegarder les modifications
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'elements/scripts.php'; ?>
</body>
</html>

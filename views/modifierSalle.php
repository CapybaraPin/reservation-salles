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
                    <form method="POST" action="" class="needs-validation" novalidate>
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

                                <button type="submit" class="btn btn-primary" name="modifierSalleOrdinateurs">
                                    <i class="fa-solid fa-save"></i> Sauvegarder
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

                        <!-- Formulaire de modification de la salle et des ordinateurs -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-secondary text-white">
                                        <h3 class="card-title">Informations sur la salle</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="container">

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

                                        </div>
                                    </div>
                                </div>

                                <?php if($salle["ID_ORDINATEUR"] != 0 ){ ?>

                                <div class="card mt-4">
                                    <div class="card-header bg-secondary text-white">
                                        <h3 class="card-title">Informations sur les ordinateurs</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="container">
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

                                        </form>

                                            <hr>

                                            <div class="row">
                                                <div class="col-10">
                                                    <h5>Informations sur les logiciels installés</h5>
                                                </div>

                                                <div class="col-2 text">
                                                    <!-- Ajouter un logiciel -->
                                                    <div class="">
                                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#ajouterLogiciel">
                                                            <i class="fa-solid fa-plus"></i> Ajouter un logiciel
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Logiciels installés -->
                                            <div class="row">
                                                <div class="form-group mb-3">
                                                    <!-- Table des logiciels installés -->
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover w-50">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Nom du logiciel</th>
                                                                    <th scope="col" width="170px" class="text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if (!empty($logicielsInstalles)){ ?>
                                                                <?php foreach ($logicielsInstalles as $logicielId) {

                                                                    // Récupérer le nom du logiciel depuis la base de données
                                                                    $logiciel = array_filter($logiciels, fn($l) => $l['identifiant'] == $logicielId["ID_LOGICIEL"]);
                                                                    $logiciel = reset($logiciel);
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $logiciel['nom'] ?></td>
                                                                        <td class="text-center">
                                                                            <!-- Bouton de suppression -->
                                                                           <form method="post" action="">
                                                                               <button type="submit" name="supprimerLogiciel" class="btn btn-danger btn-sm">
                                                                                   <i class="fa-solid fa-trash"></i> Supprimer
                                                                               </button>

                                                                               <input type="hidden" name="logicielId" value="<?= $logiciel['identifiant'] ?>">
                                                                           </form>
                                                                        </td>
                                                                    </tr>
                                                                <?php }
                                                                    } else { ?>
                                                                <tr>
                                                                    <td colspan="2" class="text-center">Aucun logiciel installé.</td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'modals/ajouterLogiciel.php'; ?>
<?php include 'elements/scripts.php'; ?>
</body>
</html>

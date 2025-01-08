<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier l'employé <?= $employe["NOM_EMPLOYE"] ?> - Consultation</title>

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
                                <h2 class="">Modification de l'employé</h2>
                                <p class="">Modifiez les informations de l'employé
                                    <b>« <?= $employe["PRENOM_EMPLOYE"] . ' ' . $employe["NOM_EMPLOYE"] ?> »</b>
                                </p>
                            </div>

                            <!-- Boutons pour revenir ou soumettre -->
                            <div class="col-12 col-lg-4 text-lg-end">
                                <button type="submit" class="btn btn-primary" name="modifierSalleOrdinateurs">
                                    <i class="fa-solid fa-save"></i> Sauvegarder
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <?php if (isset($erreurs)) { ?>
                                <div class="alert alert-danger mt-3" role="alert">
                                    <?= $erreurs ?>
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

                        <!-- Formulaire de modification de l'employé -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-secondary text-white">
                                        <h3 class="card-title">Informations sur l'employé</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="container">

                                            <!-- Nom de l'employé' -->
                                            <div class="row">
                                                <div class="form-group mb-3">
                                                    <label class="label-form" for="nom">Nom de l'employé</label>
                                                    <input class="form-control" id="nom" name="nom" type="text"
                                                           placeholder="Entrez le nom de l'employé"
                                                           value="<?= htmlspecialchars($employe['NOM_EMPLOYE']) ?>"
                                                           required>
                                                    <div class="invalid-feedback">
                                                        Veuillez entrer un nom pour l'employé.
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Prénom de l'employé -->
                                            <div class="row">
                                                <div class="form-group mb-3">
                                                    <label class="label-form" for="prenom">Prénom de l'employé</label>
                                                    <input class="form-control" id="prenom" name="prenom" type="text"
                                                           placeholder="Entrez le prénom de l'employé"
                                                           value="<?= htmlspecialchars($employe['PRENOM_EMPLOYE']) ?>"
                                                           required>
                                                    <div class="invalid-feedback">
                                                        Veuillez entrer un prénom pour l'employé.
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Numéro de téléphone de l'employé -->
                                            <div class="row">
                                                <div class="form-group mb-3">
                                                    <label class="label-form" for="telephone">Numéro de téléphone de
                                                        l'employé</label>
                                                    <input class="form-control" id="telephone" name="telephone" type="text"
                                                           placeholder="Entrez le numéro de téléphone de l'employé"
                                                           value="<?= htmlspecialchars($employe['TELEPHONE_EMPLOYE']) ?>"
                                                           required>
                                                    <div class="invalid-feedback">
                                                        Veuillez entrer un numéro de téléphone pour l'employé.
                                                    </div>
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

<?php
require_once 'utils/tableau.php';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employés</title>

    <?php include 'elements/styles.php'; ?>

</head>
<body>
<div class="container-fluid">
    <?php include 'elements/header.php'; ?>

    <!-- Section principale -->

    <div class="content">
        <!-- Bienvenue et bouton "Ajouter une salle" -->
        <div class="row">
            <div class="col-12">
                <div class="container">
                    <div class="row mt-5 mb-5">
                        <!-- Message de bienvenue -->
                        <div class="col-12 col-lg-10">
                            <h2>Gestion des employés</h2>
                            <p>Créer et gérer vos employés depuis le tableau ci-dessous.</p>
                        </div>
                        <!-- Bouton pour ajouter une salle -->
                        <div class="col-12 col-lg-2 text-lg-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterEmployee">
                                <i class="fa-solid fa-plus"></i> Ajouter un employé
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <?php if (isset($erreur)) { ?>
                            <div class="alert alert-danger mt-3" role="alert">
                                <?= $erreur ?>
                            </div>
                        <?php }
                        if (isset($erreurSuppression)) { ?>
                            <div class="alert alert-danger mt-3" role="alert">
                                <?= $erreurSuppression ?>
                            </div>
                        <?php } ?>


                        <?php if (isset($success)) { ?>
                            <div class="alert alert-success mt-3" role="alert">
                                <?= $success ?>
                            </div>
                        <?php }
                        if (isset($suppression)) { ?>
                            <div class="alert alert-success mt-3" role="alert">
                                <?= $suppression ?>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Section filtres et recherche -->
                    <div class="row mt-4 mb-4">
                        <div class="col-12 col-lg-9">
                            <!-- Filtres pour les salles -->
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

                    <!-- Tableau des employés -->
                    <?php
                    echo genererTableau($employes, $colonnes, $titre, $nbEmployes, $actions, $page, $pageMax);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale pour ajouter un employé -->
<div class="modal fade" id="ajouterEmployee" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <form method="post" action="">
                        <div class="row">
                            <h1 class="modal-title fs-5 mt-2 titre text-start" id="exampleModalLabel">Ajout d'un
                                employé</h1>
                        </div>
                        <div class="row">
                            <p class="mt-3">Pour ajouter un employé, merci de remplir les champs ci-dessous.</p>
                        </div>

                        <div class="row">
                            <!-- Champ Nom -->
                            <div class="form-group mb-1">
                                <label class="label-form" for="nom">Nom</label>
                                <input class="form-control" id="nom" name="nom" type="text" placeholder="Entrez le nom"
                                       required
                                       value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>">
                            </div>
                            <!-- Champ Prénom -->
                            <div class="form-group mt-1 mb-1">
                                <label class="label-form" for="prenom">Prenom</label>
                                <input class="form-control" id="prenom" name="prenom" type="text"
                                       placeholder="Entrez le prenom" required
                                       value="<?= isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '' ?>">
                            </div>
                            <!-- Champ Téléphone -->
                            <div class="form-group mt-1 mb-1">
                                <label class="label-form" for="telephone">Téléphone</label>
                                <input class="form-control" id="telephone" name="telephone" type="text"
                                       placeholder="Entrez le téléphone" required
                                       value="<?= isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : '' ?>">
                            </div>
                            <!-- Champ Identifiant -->
                            <div class="form-group mt-1 mb-1">
                                <label class="label-form" for="identifiant">Identifiant</label>
                                <input class="form-control" id="identifiant" name="identifiant" type="text"
                                       placeholder="Entrez l'identifiant" required
                                       value="<?= isset($_POST['identifiant']) ? htmlspecialchars($_POST['identifiant']) : '' ?>">
                            </div>
                            <!-- Champ Mot de Passe -->
                            <div class="form-group mt-1 mb-3">
                                <label class="label-form" for="motdepasse">Mot de passe</label>
                                <div class="input-group">
                                    <input class="form-control" id="motdepasse" name="motdepasse" type="password" placeholder="Entrez le mot de passe" required>
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword" aria-label="Afficher ou masquer le mot de passe">
                                        <i class="fa-solid fa-eye" id="passwordIcon"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                        <div class="row mt-3 mb-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-dark w-100" data-bs-dismiss="modal">
                                    Annuler
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fin de la modal d'ajout d'employé -->

<!-- Modal pour supprimer un employé -->
<form method="POST" action="">
    <div class="modal fade" id="modal_supprimer_employe" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Suppression employé : </h1>
                </div>
                <div class="modal-body" id="modal-body-">
                    <!-- Le message sera injecté ici par JS -->
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="employeId" value=""> <!-- Ce que va récupérer PHP pour la suppression -->
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Annuler</button>
                    <!-- Ajout de l'attribut data-reservation pour chaque employé -->
                    <button type="submit" class="btn btn-primary" name="supprimerEmploye"
                            data-reservation="<?= isset($reservations[$employe["IDENTIFIANT_EMPLOYE"]]) && $reservations[$employe["IDENTIFIANT_EMPLOYE"]] ? 'true' : 'false' ?>">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- Fin de la modal de suppression d'employé -->

</body>
<?php include 'elements/scripts.php'; ?>
</html>
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
                        <?php } ?>

                        <?php if (isset($success)) { ?>
                            <div class="alert alert-success mt-3" role="alert">
                                <?= $success ?>
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
                    <div class="row">
                        <div class="col-12">
                            <div class="border border-1 rounded rounded-4 shadow-sm">
                                <!-- Titre du tableau -->
                                <p class="p-3 pb-0 fw-bold">Les employés
                                    <button class="btn disabled badge text-primary text-wrap">20 employés</button>
                                </p>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="table-light">
                                        <!-- En-tête du tableau -->
                                        <tr>
                                            <th><input type="checkbox" name="" id="" class="ms-2 form-check-input"></th>
                                            <th>Identifiant</th>
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Numéro de téléphone</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <!-- Première ligne de données (exemple) -->
                                        <?php foreach ($employes as $employe) { ?>
                                            <tr>
                                                <td><input type="checkbox" name="" id="" class="ms-2 form-check-input"></td>
                                                <td><?= $employe["IDENTIFIANT_EMPLOYE"] ?></td>
                                                <td><?= $employe["NOM_EMPLOYE"] ?></td>
                                                <td><?= $employe["PRENOM_EMPLOYE"] ?></td>
                                                <td><?= $employe["TELEPHONE_EMPLOYE"] ?></td>
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
                                            <button class="btn btn-outline-dark d-lg-none">
                                                <i class="fa-solid fa-arrow-left"></i>
                                            </button>
                                        </div>
                                        <div class="col-3 d-lg-block d-none">
                                            <button class="btn btn-outline-dark">
                                                <i class="fa-solid fa-arrow-left"></i> Précédent
                                            </button>
                                        </div>
                                        <div class="col-6 d-flex justify-content-center">
                                            <nav class="text-center">
                                                <ul class="pagination-page">
                                                    <!-- Liens de pagination -->
                                                    <li class="pagination-item active"><a class="page-link" href="#">1</a></li>
                                                    <li class="pagination-item"><a class="page-link" href="#">2</a></li>
                                                    <li class="pagination-item"><a class="page-link" href="#">3</a></li>
                                                    <li class="pagination-item"><a class="page-link" href="#">...</a></li>
                                                    <li class="pagination-item"><a class="page-link" href="#">8</a></li>
                                                    <li class="pagination-item"><a class="page-link" href="#">9</a></li>
                                                    <li class="pagination-item"><a class="page-link" href="#">10</a></li>
                                                </ul>
                                            </nav>
                                        </div>
                                        <div class="col-3 text-end d-lg-block d-none">
                                            <button class="btn btn-outline-dark">Suivant
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </button>
                                        </div>
                                        <div class="col-3 text-end d-lg-none d-block">
                                            <button class="btn btn-outline-dark d-lg-none">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </button>
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

<!-- Modale pour ajouter un employé -->
<div class="modal fade" id="ajouterEmployee" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <form method="post" action="">
                        <div class="row">
                            <h1 class="modal-title fs-5 mt-2 titre text-start" id="exampleModalLabel">Ajout d'un employé</h1>
                        </div>
                        <div class="row">
                            <p class="mt-3">Pour ajouter un employé, merci de remplir les champs ci-dessous.</p>
                        </div>

                        <div class="row">
                            <!-- Champ Nom -->
                            <div class="form-group mb-1">
                                <label class="label-form" for="nom">Nom</label>
                                <input class="form-control" id="nom" name="nom" type="text" placeholder="Entrez le nom" required
                                       value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>">
                            </div>
                            <!-- Champ Prénom -->
                            <div class="form-group mt-1 mb-1">
                                <label class="label-form" for="prenom">Prenom</label>
                                <input class="form-control" id="prenom" name="prenom" type="text" placeholder="Entrez le prenom" required
                                       value="<?= isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '' ?>">
                            </div>
                            <!-- Champ Téléphone -->
                            <div class="form-group mt-1 mb-1">
                                <label class="label-form" for="telephone">Téléphone</label>
                                <input class="form-control" id="telephone" name="telephone" type="text" placeholder="Entrez le téléphone" required
                                       value="<?= isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : '' ?>">
                            </div>
                            <!-- Champ Identifiant -->
                            <div class="form-group mt-1 mb-1">
                                <label class="label-form" for="identifiant">Identifiant</label>
                                <input class="form-control" id="identifiant" name="identifiant" type="text" placeholder="Entrez l'identifiant"
                                       value="<?= isset($_POST['identifiant']) ? htmlspecialchars($_POST['identifiant']) : '' ?>">
                            </div>
                            <!-- Champ Mot de Passe -->
                            <div class="form-group mt-1 mb-3">
                                <label class="label-form" for="motdepasse">Mot de passe</label>
                                <input class="form-control" id="motdepasse" name="motdepasse" type="password" placeholder="Entrez le mot de passe" >
                            </div>
                        </div>
                        <div class="row mt-3 mb-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-dark w-100" data-bs-dismiss="modal">Annuler</button>
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

</body>
<?php include 'elements/scripts.php'; ?>
</html>
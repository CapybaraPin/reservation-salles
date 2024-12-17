<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activites</title>

    <?php include 'elements/styles.php'; ?>
</head>
<body>
<div class="container-fluid">
    <?php include 'elements/header.php'; ?>

    <!-- Section principale -->

    <div class="content">
        <!-- Bienvenue et bouton "Ajouter une Activité" -->
        <div class="row">
            <div class="col-12">
                <div class="container">
                    <div class="row mt-5 mb-5">
                        <!-- Message de bienvenue -->
                        <div class="col-12 col-lg-10">
                            <h2>Gestion des activités</h2>
                            <p>Track, manage and forecast your customers and orders.</p>
                        </div>
                        <!-- Bouton pour ajouter une Activité -->
                        <div class="col-12 col-lg-2 text-lg-end">
                            <button class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Ajouter une Activité
                            </button>
                        </div>
                    </div>

                    <!-- Section filtres et recherche -->
                    <div class="row mt-4 mb-4">
                        <div class="col-12 col-lg-9">
                            <!-- Filtres pour les Salles -->
                            <div class="btn border border-1 shadow-sm me-2">
                                Salle : "picasso" <i class="fa-solid fa-xmark text-primary ps-2"></i>
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

                    <!-- Tableau des Activités -->
                    <div class="row">
                        <div class="col-12">
                            <div class="border border-1 rounded rounded-4 shadow-sm">
                                <!-- Titre du tableau -->
                                <p class="p-3 pb-0 fw-bold">Les Activités
                                    <button class="btn disabled badge text-primary text-wrap">70 Activités</button>
                                </p>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="table-light">
                                        <!-- En-tête du tableau -->
                                        <tr>
                                            <th><input type="checkbox" name="" id="" class="ms-2 form-check-input"></th>
                                            <th>Identifiant</th>
                                            <th>Type</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <!-- Première ligne de données (exemple) -->
                                        <tr>
                                            <td><input type="checkbox" name="" id="" class="ms-2 form-check-input"></td>
                                            <td>1</td>
                                            <td>Yoga</td>
                                        </tr>
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
                                                    <li class="pagination-item active"><a class="page-link"
                                                                                          href="#">1</a></li>
                                                    <li class="pagination-item"><a class="page-link" href="#">2</a></li>
                                                    <li class="pagination-item"><a class="page-link" href="#">3</a></li>
                                                    <li class="pagination-item"><a class="page-link" href="#">...</a>
                                                    </li>
                                                    <li class="pagination-item"><a class="page-link" href="#">8</a></li>
                                                    <li class="pagination-item"><a class="page-link" href="#">9</a></li>
                                                    <li class="pagination-item"><a class="page-link" href="#">10</a>
                                                    </li>
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
</body>
<?php include 'elements/scripts.php'; ?>
</html>
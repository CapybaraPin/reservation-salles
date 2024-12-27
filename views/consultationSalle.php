<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ConsultationSalles</title>

        <?php include __DIR__ . '/../views/elements/styles.php'; ?>
    </head>
    <body>
        <div class="container-fluid">
            <?php include __DIR__ . '/../views/elements/header.php'; ?>
            <!-- Formulaire pour ajouter un employé -->
            <div class="container mt-5">
                <form method="post" action="">
                    <div class="row">
                        <h1 class="fs-5 mt-2 titre text-start">Information d'une salle</h1>
                    </div>
                    <div class="row">
                        <p class="mt-3">Pour consulter ou modifier les données d'une salle</p>
                    </div>

                    <div class="row">
                        <!-- Champ Nom -->
                        <div class="form-group mb-1">
                            <label class="label-form" for="nom">Nom de la salle</label>
                            <input class="form-control" id="nomSalle" name="nomSalle" type="text"
                                   required
                                   value="">
                        </div>
                        <!-- Champ Capacitée -->
                        <div class="form-group mt-1 mb-1">
                            <label class="label-form" for="prenom">Capacitée</label>
                            <input class="form-control" id="capacite" name="capacite" type="number"
                                   required
                                   value="">
                        </div>
                        <!-- Champ video projecteur -->
                        <div class="form-group mt-1 mb-1">
                            <label class="label-form" for="telephone">Présence d'un vidéo projecteur</label>
                            <input class="form-control" id="videoProjecteur" name="videoProjecteur" type="text"
                                   required
                                   value="">
                        </div>
                        <!-- Champ écran XXL -->
                        <div class="form-group mt-1 mb-1">
                            <label class="label-form" for="identifiant">Présence d'un écran XXL</label>
                            <input class="form-control" id="écranXXL" name="écranXXL" type="text"
                                   required
                                   value="">
                        </div>
                        <!-- Champ nombre d'ordinateur  -->
                        <div class="form-group mt-1 mb-1">
                            <label class="label-form" for="identifiant">Nombre d'ordinateur dans la salle</label>
                            <input class="form-control" id="nbOrdinateur" name="nbOrdinateur" type="number"
                                   required
                                   value="">
                        </div>
                        <!-- Champ imprimante  -->
                        <div class="form-group mt-1 mb-1">
                            <label class="label-form" for="identifiant">Présence d'une imprimante dans la salle</label>
                            <input class="form-control" id="imprimante" name="imprimante" type="text"
                                   required
                                   value="">
                        </div>
                        <!-- Champ type d'ordinateur  -->
                        <div class="form-group mt-1 mb-1">
                            <label class="label-form" for="identifiant">Type de sous dans la salle</label>
                            <input class="form-control" id="typeOrdinateur" name="typeOrdinateur" type="text"
                                   required
                                   value="">
                        </div>
                        <!-- Champ Logiciel  -->
                        <div class="form-group mt-1 mb-1">
                            <label class="label-form" for="identifiant">Logiciel disponible sur les ordiateur</label>
                            <input class="form-control" id="logiciel" name="logiciel" type="text"
                                   required
                                   value="">
                        </div>
                    </div>
                    <div class="row mt-3 mb-2">
                        <div class="col-6">
                            <button type="reset" class="btn btn-outline-dark w-100">Annuler</button>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary w-100">Modifier</button>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </body>
</html>

<!-- Modale pour ajouter une salle -->
<form method="post" action="">
    <div class="modal fade" id="ajouterSalle" tabindex="-1" aria-labelledby="ModalAjouterSalle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <h1 class="modal-title fs-5 mt-2 titre text-start" id="ModalAjouterSalle">Ajout d'une salle</h1>
                        </div>
                        <div class="row">
                            <p class="mt-3">Pour ajouter une salle, merci de remplir les champs ci-dessous.</p>
                        </div>

                        <div class="row">
                            <!-- Champ Nom de la salle -->
                            <div class="form-group mb-1">
                                <label class="label-form" for="nom">Nom de la salle</label>
                                <input class="form-control" id="nom" name="nom" type="text" placeholder="Entrez le nom"
                                       required
                                       value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>">
                            </div>
                            <!-- Champ Capacite -->
                            <div class="form-group mt-1 mb-1">
                                <label class="label-form" for="capacite">Capacité de la salle</label>
                                <input class="form-control" id="capacite" name="capacite" type="number"
                                       placeholder="Entrez la capicité de la salle" required
                                       value="<?= isset($_POST['capacite']) ? htmlspecialchars($_POST['capacite']) : '' ?>">
                            </div>

                            <!-- Champ du vidéo Projecteur -->
                            <div class="form-group mt-2 mb-2">
                                <div class="form-check">
                                    <input name="videoProjecteur" class="form-check-input" type="checkbox" value="" id="videoProjecteur">
                                    <label class="form-check-label label-form" for="videoProjecteur">
                                        Possède un vidéo projecteur ?
                                    </label>
                                </div>
                            </div>

                            <!-- Champ de l'écran XXL -->
                            <div class="form-group mt-2 mb-2">
                                <div class="form-check">
                                    <input name="ecranXXL" class="form-check-input" type="checkbox" value="" id="ecranXXL">
                                    <label class="form-check-label label-form" for="ecranXXL">
                                        Possède un écran XXL ?
                                    </label>
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
                                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#ajouterOrdinateur">Suivant </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal gestion des ordinateurs -->

    <div class="modal fade" id="ajouterOrdinateur" tabindex="-1" aria-labelledby="ModalAjouterOrdinateur" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <h1 class="modal-title fs-5 mt-2 titre text-start" id="ModalAjouterOrdinateur">Ajout des ordinateurs</h1>
                        </div>
                        <div class="row">
                            <p class="mt-3">Pour ajouter une salle, merci de remplir les champs ci-dessous.</p>
                        </div>

                        <div class="row">
                            <!-- Champ Nom de la salle -->
                            <div class="form-group mb-1">
                                <label class="label-form" for="nbOrdinateurs">Nombre d'ordinateurs dans la salle</label>
                                <input class="form-control" id="nbOrdinateurs" name="nbOrdinateurs" type="number" placeholder="Entrez le nombre d'ordinateurs"
                                       required
                                       value="<?= isset($_POST['nbOrdinateurs']) ? htmlspecialchars($_POST['nbOrdinateurs']) : '' ?>">
                            </div>

                            <!-- Champ Capacite -->
                            <div class="form-group mt-1 mb-1">
                                <label class="label-form" for="logiciels">Logiciels présents sur les ordinateurs</label>
                                <select class="form-select" id="logiciels" name="logiciels[]" multiple aria-label="multiple select example">
                                    <option selected>Sélectionnez le(s) logiciel(s)</option>
                                    <?php foreach ($logiciels as $logiciel) : ?>
                                        <option value="<?= $logiciel['identifiant'] ?>"><?= $logiciel['nom'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Champ du vidéo Projecteur -->
                            <div class="form-group mt-2 mb-2">
                                <div class="form-check">
                                    <input name="imprimante" class="form-check-input" type="checkbox" value="" id="imprimante">
                                    <label class="form-check-label label-form" for="imprimante">
                                        Possède une imprimante ?
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mt-1 mb-1">
                                <label class="label-form" for="typeOrdinateur">Type d'ordinateur</label>
                                <select class="form-select" id="typeOrdinateur" name="typeOrdinateur">
                                    <option selected>Sélectionnez un type d'ordinateur</option>
                                    <?php foreach ($typesOrdinateur as $type) : ?>
                                        <option value="<?= $type['identifiant'] ?>"><?= $type['type'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>
                        <div class="row mt-3 mb-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-dark w-100" data-bs-dismiss="modal">
                                    Précédent
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary w-100">Envoyer </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Fin de la modal d'ajout d'employé -->
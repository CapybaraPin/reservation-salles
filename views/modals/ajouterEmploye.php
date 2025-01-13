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
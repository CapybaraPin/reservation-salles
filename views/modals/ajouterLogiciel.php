<form method="post" action="">
    <div class="modal fade" id="ajouterLogiciel" tabindex="-1" aria-labelledby="ModalAjouterLogiciel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <h1 class="modal-title fs-5 mt-2 titre text-start" id="ModalAjouterSalle">Ajout d'un logiciel</h1>
                        </div>
                        <div class="row">
                            <p class="mt-3">Pour ajouter un logiciel, merci de remplir les champs ci-dessous.</p>
                        </div>

                        <!-- Sélectionner un logiciel -->
                        <div class="row mb-3">
                            <label for="selectLogiciel">Choisir un logiciel :</label>

                                <select id="selectLogiciel" name="logicielId" class="form-select" required>
                                    <option value="" disabled selected>Sélectionnez un logiciel</option>
                                    <?php foreach ($logiciels as $logiciel) : ?>
                                        <option value="<?= $logiciel['identifiant']; ?>">
                                            <?= $logiciel['nom']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="autre">Autre</option>
                                </select>
                                <div class="form-group mb-1 mt-2 d-none" id="groupnomlogiciel">
                                    <label class="label-form" for="nomLogiciel">Nom du logiciel</label>
                                    <input class="form-control" id="nomlogiciel" name="nomLogiciel" type="text" placeholder="Entrez le nom du logiciel"
                                           value="<?= isset($_POST['nomLogiciel']) ? htmlspecialchars($_POST['nomLogiciel']) : '' ?>">
                                </div>
                                <div class="invalid-feedback">
                                    Veuillez sélectionner un logiciel.
                                </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="ajouterLogiciel" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- Fin de la modal d'ajout d'un logiciel -->

<!-- Modal de supression d'un logiciel -->
<form method="post" action="">
    <div class="modal fade" id="supprimerlogiciel" tabindex="-1" aria-labelledby="ModalAjouterLogiciel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <h1 class="modal-title fs-5 mt-2 titre text-start" id="ModalAjouterSalle">Supression d'un logiciel inutilisé</h1>
                        </div>
                        <div class="row">
                            <p class="mt-3">Pour supprimer un logiciel inutilisé de la liste des logiciels disponibles, veuillez remplir les champs ci-dessous.</p>
                        </div>

                        <!-- Sélectionner un logiciel -->
                        <div class="row mb-3">
                            <label for="selectLogiciel">Choisir un logiciel :</label>

                            <select id="selectLogiciel" name="logicielId" class="form-select" required>
                                <?php if(count($SuprLogiciels) > 0) { ?>
                                <option value="" disabled selected>Sélectionnez un logiciel à supprimer</option>
                                <?php foreach ($SuprLogiciels as $logiciel) : ?>
                                    <option value="<?= $logiciel['identifiant']; ?>">
                                        <?= $logiciel['nom']; ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php } else { ?>
                                <option value="" disabled selected>Aucun logiciel à supprimer</option>
                                <?php } ?>
                            </select>
                            <div class="invalid-feedback">
                                Veuillez sélectionner un logiciel.
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="suprLogiciel" class="btn btn-danger <?php if(count($SuprLogiciels) == 0) { ?> disabled <?php } ?>">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- Fin de la modal d'ajout d'un logiciel -->


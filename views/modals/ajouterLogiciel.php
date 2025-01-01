<form method="post" action="">
    <div class="modal fade" id="ajouterLogiciel" tabindex="-1" aria-labelledby="ModalAjouterLogiciel" aria-hidden="true">
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
                                </select>
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

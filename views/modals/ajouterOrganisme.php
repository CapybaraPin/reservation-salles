<!-- Modal pour ajouter une réservation -->
<form method="post" action="">
    <div class="modal fade" id="ajouterOrganisme" tabindex="-1" aria-labelledby="ajouterOrganisme" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body me-2">
                    <div class="row">
                        <h1 class="modal-title fs-5 mt-2 titre text-start" id="ajouterOrganisme">
                            Ajouter un organisme
                        </h1>
                    </div>
                    <div class="row">
                        <!-- Champ Nom organisation -->
                        <div class="form-group mb-1">
                            <label class="label-form" for="nomOrganisation">Nom de l'organisation</label>
                            <input class="form-control" id="nomOrganisation" name="nomOrganisation" type="text" placeholder="Entrez le nom de l'organisation">
                        </div>
                        <!-- Champ Nom intervenant -->
                        <div class="form-group mb-1">
                            <label class="label-form" for="nomIntervenant">Nom du intervenant</label>
                            <input class="form-control" id="nomIntervenant" name="nomIntervenant" type="text" placeholder="Entrez le nom de intervenant">
                        </div>
                        <!-- Champ Prénom intervenant -->
                        <div class="form-group mt-1 mb-1">
                            <label class="label-form" for="prenomIntervenant">Prénom du intervenant</label>
                            <input class="form-control" id="prenomIntervenant" name="prenomIntervenant" type="text" placeholder="Entrez le prénom de intervenant">
                        </div>
                        <!-- Champ téléphone intervenant -->
                        <div class="form-group mt-1 mb-1">
                            <label class="label-form" for="telIntervenant">Numéro de téléphone du intervenant</label>
                            <input class="form-control" id="telIntervenant" name="telIntervenant" type="text" placeholder="Entrez le numéro de téléphone du intervenant">
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="row mt-3 mb-2">
                    <div class="col-6">
                        <button type="button" id="btn-back" class="btn btn-outline-dark w-100">
                            Fermer
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" id="btn-next" class="btn btn-primary w-100">Suivant</button>
                        <button type="submit" id="btn-reserver" class="btn btn-primary w-100" name="ajouterReservation" style="display: none;">Réserver</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- Fin de la modal d'ajout réservation -->

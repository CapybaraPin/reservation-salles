<!-- Modal pour ajouter une réservation -->
<form method="post" action="">
    <div class="modal fade" id="ajouterReservation" tabindex="-1" aria-labelledby="ModalAjouterReservation" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                            <!-- Étape 1 : Crénaux et Salle -->
                            <div id="step-1">
                                <div class="row">
                                    <h1 class="modal-title fs-5 mt-2 titre text-start" id="ModalAjouterReservation">
                                        Ajout d'une réservation
                                    </h1>

                                </div>
                                <div class="row">
                                    <p class="mt-3">Vous ajoutez une réservation à votre nom, suivez le formulaire d’ajout de réservation.</p>
                                </div>
                                <div class="row">
                                    <!-- Champ Crénaux -->
                                    <div class="form-group mb-1">
                                        <label class="label-form" for="dateDebut">Crénaux</label>
                                        <input class="form-control mb-1" id="dateDebut" name="dateDebut" type="datetime-local" placeholder="Date de début" required>
                                        <input class="form-control" id="dateFin" name="dateFin" type="datetime-local" placeholder="Date de fin" required>
                                    </div>
                                    <!-- Champ Salle -->
                                    <div class="form-group mt-1 mb-1">
                                        <label class="label-form" for="salle">Salle</label>
                                        <select class="form-select" id="salle" name="salle">
                                            <option value="0">Sélectionner une salle</option>
                                            <?php foreach ($salles as $salle) : ?>
                                                <option value="<?= $salle['ID_SALLE'] ?>"><?= $salle['NOM_SALLE'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Étape 2 : Type de réservation -->
                            <div id="step-2" style="display: none;">
                                <div class="row">
                                    <h1 class="modal-title fs-5 mt-2 titre text-start" id="ModalAjouterReservation">
                                        Ajout d'une réservation
                                    </h1>
                                </div>
                                <div class="row">
                                    <p class="mt-3">Vous ajoutez une réservation à votre nom, suivez le formulaire d’ajout de réservation.</p>
                                </div>
                                <div class="row">
                                    <!-- Type de réservation -->
                                    <div class="form-group mt-1 mb-1">
                                        <label class="label-form" for="typeReservation">Type de réservation</label>
                                        <select class="form-select" id="typeReservation" name="typeReservation">
                                            <option value="0">Sélectionner le type de réservation</option>
                                            <?php foreach ($activites as $activite) : ?>
                                                <option value="<?= $activite['IDENTIFIANT_ACTIVITE'] ?>"><?= $activite['TYPE_ACTIVITE'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        <!-- Étape 3.1 : Détails pour une activité spécifique -->
                        <div id="step-3-1" class="step-3" style="display: none;">
                            <div class="row">
                                <h1 class="modal-title fs-5 mt-2 titre text-start" id="ModalAjouterReservation">
                                    Ajout d'une réservation
                                </h1>
                            </div>
                            <div class="row">
                                <p class="mt-3">Vous ajoutez une réservation à votre nom, suivez le formulaire d’ajout de réservation.</p>
                            </div>
                            <div class="row">
                                <div class="form-group mb-1">
                                    <label class="label-form" for="formateur">Formateur</label>
                                    <select class="form-select" id="formateur" name="formateur">
                                        <option value="0">Sélectionner un formateur</option>
                                        <option value="-1">Créer un formateur</option>
                                        <?php foreach ($formateurs as $formateur) { ?>
                                            <option value="<?= $formateur['ID_INDIVIDU'] ?>"><?= $formateur['NOM_INDIVIDU'] . ' ' . $formateur['PRENOM_INDIVIDU'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <!-- Champ Nom formateur -->
                                <div class="form-group mb-1 mt-1 d-none" id="divNomFormateur">
                                    <label class="label-form" for="nomIndividu" id="labelNomIndividu">Nom du formateur</label>
                                    <input class="form-control" id="nomIndividu" name="nomIndividu" type="text" placeholder="Entrez le nom du formateur">
                                </div>
                                <!-- Champ Prénom formateur -->
                                <div class="form-group mt-1 mb-1 d-none" id="divPrenomFormateur">
                                    <label class="label-form" for="prenomIndividu" id="labelPrenomIndividu">Prénom du formateur</label>
                                    <input class="form-control" id="prenomIndividu" name="prenomIndividu" type="text" placeholder="Entrez le prénom">
                                </div>
                                <!-- Champ Prénom formateur -->
                                <div class="form-group mt-1 mb-1 d-none" id="divTelFormateur">
                                    <label class="label-form" for="telIndividu" id="labelTelIndividu">Numéro de téléphone du formateur</label>
                                    <input class="form-control" id="telIndividu" name="telIndividu" type="text" placeholder="Entrez le numéro de téléphone du formateur">
                                </div>
                                <!-- Champ Sujet formation -->
                                <div class="form-group mt-1 mb-1" id="divSujetFormateur">
                                    <label class="label-form" for="sujetFormation" id="labelSujetFormation">Sujet de la formation</label>
                                    <input class="form-control" id="sujetFormation" name="sujetFormation" type="text" placeholder="Sujet de la formation">
                                </div>
                            </div>
                        </div>

                        <!-- Étape 3.2 : Détails pour une autre activité -->
                        <div id="step-3-2" class="step-3" style="display: none;">
                            <div class="row">
                                <h1 class="modal-title fs-5 mt-2 titre text-start" id="ModalAjouterReservation">
                                    Ajout d'une réservation
                                </h1>
                            </div>
                            <div class="row">
                                <p class="mt-3">Vous ajoutez une réservation à votre nom, suivez le formulaire d’ajout de réservation.</p>
                            </div>
                            <div class="row">
                                <div class="form-group mb-1">
                                    <label class="label-form" for="organisme">Organisme</label>
                                    <select class="form-select" id="organisme" name="organisme">
                                        <option value="0">Sélectionner un organisme</option>
                                        <option value="-1">Créer un organisme</option>
                                        <?php foreach ($organismes as $organisme) { ?>
                                            <option value="<?= $organisme['IDENTIFIANT_ORGANISME'] ?>"><?= $organisme['NOM_ORGANISME'] . " - " . $organisme['NOM_INTERLOCUTEUR'] . " " . $organisme['PRENOM_INTERLOCUTEUR'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <!-- Champ Nom organisation -->
                                <div class="form-group mb-1 mt-1 d-none" id="divNomOrganisme">
                                    <label class="label-form" for="nomOrganisation">Nom de l'organisation</label>
                                    <input class="form-control" id="nomOrganisation" name="nomOrganisation" type="text" placeholder="Entrez le nom de l'organisation">
                                </div>
                                <!-- Champ Nom intervenant -->
                                <div class="form-group mb-1 mt-1 d-none" id="divNomIntervenant">
                                    <label class="label-form" for="nomIntervenant">Nom du intervenant</label>
                                    <input class="form-control" id="nomIntervenant" name="nomIntervenant" type="text" placeholder="Entrez le nom de intervenant">
                                </div>
                                <!-- Champ Prénom intervenant -->
                                <div class="form-group mt-1 mb-1 d-none" id="divPrenomIntervenant">
                                    <label class="label-form" for="prenomIntervenant">Prénom du intervenant</label>
                                    <input class="form-control" id="prenomIntervenant" name="prenomIntervenant" type="text" placeholder="Entrez le prénom de intervenant">
                                </div>
                                <!-- Champ téléphone intervenant -->
                                <div class="form-group mt-1 mb-1 d-none" id="divTelIntervenant">
                                    <label class="label-form" for="telIntervenant">Numéro de téléphone du intervenant</label>
                                    <input class="form-control" id="telIntervenant" name="telIntervenant" type="text" placeholder="Entrez le numéro de téléphone du intervenant">
                                </div>
                                <!-- Champ Sujet du prêt ou location -->
                                <div class="form-group mt-1 mb-1">
                                    <label class="label-form" for="sujetLocation">Sujet de la Location</label>
                                    <input class="form-control" id="sujetLocation" name="sujetLocation" type="text" placeholder="Sujet de la location">
                                </div>
                            </div>
                        </div>

                        <!-- Étape 3 par défaut -->
                        <div id="step-3-default" class="step-3" style="display: none;">
                            <div class="row">
                                <h1 class="modal-title fs-5 mt-2 titre text-start" id="ModalAjouterReservation">
                                    Ajout d'une réservation
                                </h1>
                            </div>
                            <div class="row">
                                <p class="mt-3">Vous ajoutez une réservation à votre nom, suivez le formulaire d’ajout de réservation.</p>
                            </div>
                            <div class="row">
                                <!-- Champ description -->
                                <div class="form-group mt-1 mb-1">
                                    <label class="label-form" for="description">Description</label>
                                    <input class="form-control" id="description" name="description" type="text" placeholder="Description">
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
        </div>
    </div>
</form>
<!-- Fin de la modal d'ajout réservation -->

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
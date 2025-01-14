<!-- Modal pour supprimer une réservation -->
<form method="POST" action="">
    <div class="modal fade" id="modal_supprimer_reservation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <h1 class="modal-title fs-5 mt-2 titre text-start" id="exampleModalLabel">Supprimer une réservation</h1>
                        </div>
                        <div class="row">
                            <p class="mt-3">Êtes-vous sûr de vouloir supprimer cette réservation ?</p>
                        </div>
                        <div class="row mt-3 mb-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-dark w-100" data-bs-dismiss="modal">
                                    Annuler
                                </button>
                            </div>
                            <div class="col-6">
                                <input type="hidden" name="idReservation" value="">
                                <button name="supprimerReservation" type="submit" class="btn btn-danger w-100">Supprimer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- Fin de la modal de suppression d'une réservation -->
<?php

namespace controllers;

/**
 * Contrôleur pour la page des activités
 */
class ExportController extends Controller
{
    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get()
    {
        $nbReservations = $this->reservationModel->getNbReservations();
        $nbSalles = $this->salleModel->getNbSalles();
        $nbEmployes = $this->employeModel->getNbEmployes();
        $nbActivites = $this->activiteModel->getNbActivites();

        require __DIR__ . '/../views/exportation.php';
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post()
    {
        $this->deconnexion();
        $this->get();
    }

    /**
     * Exporte les données au format CSV
     */
    public function exportation()
    {
        $this->exportationModel->exportationDonnees();
    }
}
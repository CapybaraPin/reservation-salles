<?php

namespace controllers;

use services\Auth;
use services\Config;

/**
 * Contrôleur pour la page des réservations
 */
class ReservationsController extends Controller
{
    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get()
    {
        global $db;

        // Récupération du nombre total de réservations
        $nbReservations = $db->getNbReservations();
        $NB_LIGNES = Config::get('NB_LIGNES');

        // Récupération de la page courante
        $page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
        $page = max(intval($page), 1);
        $pageMax = ceil($nbReservations / $NB_LIGNES);
        $page = min($page, $pageMax);

        // Récupération des réservations
        $reservations = $db->getReservations(($page - 1) * $NB_LIGNES);
        $nbLignesPage = $NB_LIGNES;

        require __DIR__ . '/../views/reservations.php';
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post()
    {
        $this->deconnexion();
        require __DIR__ . '/../views/reservations.php';
    }
}
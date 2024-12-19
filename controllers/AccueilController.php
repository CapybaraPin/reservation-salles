<?php

namespace controllers;

use services\Config;

/**
 * Class AccueilController
 *
 * Ce contrôleur gère la page d'accueil de l'application.
 *
 * @package controllers
 */
class AccueilController extends Controller
{
    public function get()
    {
        global $db;

        // Récupération du nombre total de réservations
        $nbReservations = $db->getNbReservations();

        // Récupération de la page courante
        $page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
        $page = max(intval($page), 1);
        $pageMax = ceil($nbReservations / $db::NB_LIGNES);
        $page = min($page, $pageMax);

        // Récupération des réservations
        $reservations = $db->getReservations(($page - 1) * $db::NB_LIGNES);
        $nbLignesPage = $db::NB_LIGNES;
        require __DIR__ . '/../views/accueil.php';
    }

    public function post()
    {
        $this->deconnexion();
        require __DIR__ . '/../views/accueil.php';
    }
}
<?php

namespace controllers;

use PDO;
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
        $nbReservations = $db->getNbReservations($_SESSION['userIndividuId']);
        $NB_LIGNES = Config::get('NB_LIGNES');

        // Récupération de la page courante
        $page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
        $page = max(intval($page), 1);
        $pageMax = ceil($nbReservations / $NB_LIGNES);
        $page = min($page, $pageMax);

        // Récupération des réservations
        $filre = ["reservation.idEmploye" => [$_SESSION['userIndividuId'], PDO::PARAM_INT]];
        $reservations = $db->getReservations(($page - 1) * $NB_LIGNES, $filre);
        $nbLignesPage = $NB_LIGNES;
        require __DIR__ . '/../views/accueil.php';
    }

    public function post()
    {
        $this->deconnexion();
        require __DIR__ . '/../views/accueil.php';
    }
}
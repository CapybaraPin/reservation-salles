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
        // Récupération de la liste des réservations
        global $db;
        $reservations = $db->getReservations();
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
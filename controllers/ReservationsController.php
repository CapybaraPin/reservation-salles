<?php

namespace controllers;

use services\Auth;
use services\Config;

/**
 * Contrôleur pour la page des réservations
 */
class ReservationsController
{
    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get()
    {
        require __DIR__ . '/../views/reservations.php';
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post()
    {
        require __DIR__ . '/../views/reservations.php';
    }
}
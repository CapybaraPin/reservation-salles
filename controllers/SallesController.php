<?php

namespace controllers;

use services\Auth;
use services\Config;

/**
 * Contrôleur pour la page des salles
 */
class SallesController
{
    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get()
    {
        require __DIR__ . '/../views/salles.php';
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post()
    {
       require __DIR__ . '/../views/salles.php';
    }
}
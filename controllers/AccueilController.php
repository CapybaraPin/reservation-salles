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
class AccueilController
{
    public function get()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: " . Config::get('APP_URL') . "/auth");
        }

        require __DIR__ . '/../views/accueil.php';
    }

    public function post()
    {
        require __DIR__ . '/../views/accueil.php';
    }
}
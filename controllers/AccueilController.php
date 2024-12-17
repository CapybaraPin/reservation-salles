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
        require __DIR__ . '/../views/accueil.php';
    }

    public function post()
    {
        $this->deconnexion();
        require __DIR__ . '/../views/accueil.php';
    }
}
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
}
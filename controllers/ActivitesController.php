<?php

namespace controllers;

/**
 * Contrôleur pour la page des activités
 */
class ActivitesController extends Controller
{
    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get()
    {
        // Récupération de la liste des activités
        global $db;
        $activites = $db->getActivites();
        require __DIR__ . '/../views/activites.php';
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post()
    {
        $this->deconnexion();
        require __DIR__ . '/../views/activites.php';
    }
}
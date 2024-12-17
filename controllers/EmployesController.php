<?php

namespace controllers;

use services\Auth;
use services\Config;

/**
 * Contrôleur pour la page des employés
 */
class EmployesController extends Controller
{
    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get()
    {
        // Récupération de la liste des employés
        global $db;
        $employes = $db->getEmployes();

        require __DIR__ . '/../views/employes.php';
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post()
    {
        $this->deconnexion();
        require __DIR__ . '/../views/employes.php';
    }
}
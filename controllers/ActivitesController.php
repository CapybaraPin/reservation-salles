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

        $titre = "Activités";

        $colonnes = [
            "IDENTIFIANT_ACTIVITE" => "Identifiant",
            "TYPE_ACTIVITE" => "Type",
        ];

        $activites = $this->activiteModel->getActivites();
        $nbActivites = $this->activiteModel->getNbActivites();

        foreach ($activites as &$activite) {
            $activite['ID'] = $activite['IDENTIFIANT_ACTIVITE'];
        }

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
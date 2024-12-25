<?php

namespace controllers;

use services\Auth;
use services\Config;

/**
 * Contrôleur pour la page des salles
 */
class SallesController extends Controller
{
    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get()
    {
        $titre = 'Salles';
        $colonnes = [
            "ID_SALLE" => 'Identifiant',
            "NOM_SALLE" => 'Nom',
            "CAPACITE" => 'Capacité',
            "VIDEO_PROJECTEUR" => 'Vidéo Projecteur',
            "ECRAN_XXL" => 'Ecran XXL',
        ];

        $nbSalles = $this->salleModel->getNbSalles();
        list ($page, $pageMax) = $this->getPagination($nbSalles);
        $nbLignesPage = Config::get('NB_LIGNES');
        $salles = $this->salleModel->getSalles(($page - 1) * $nbLignesPage);

        // Création des actions pour chaque salle
        // et ajout des informations demandées par les colonnes
        $actions = [];
        foreach ($salles as &$salle) {
            $salle['ID'] = $salle['ID_SALLE'];

            $salle['VIDEO_PROJECTEUR'] = $salle['VIDEO_PROJECTEUR'] == "1" ? 'Oui' : 'Non';
            $salle['ECRAN_XXL'] = $salle['ECRAN_XXL'] == "1" ? 'Oui' : 'Non';

            if ($salle['ID_ORDINATEUR'] != 0) {
                $actions[$salle['ID_SALLE']]['info'] = [
                    'attributs' => ['class' => 'btn btn-nav', 'title' => 'Plus d\'informations'],
                    'icone' => 'fa-solid fa-circle-info'
                ];
            }

            $actions[$salle['ID_SALLE']]['modifier'] = [
                'attributs' => ['class' => 'btn', 'title' => 'Modifier'],
                'icone' => 'fa-solid fa-pen'
            ];

            $actions[$salle['ID_SALLE']]['supprimer'] = [
                'attributs' => ['class' => 'btn btn-nav', 'title' => 'Supprimer'],
                'icone' => 'fa-solid fa-trash-can'
            ];

        }

        $typesOrdinateur = $this->ordinateurModel->getTypesOrdinateur();
        $logiciels = $this->ordinateurModel->getLogiciels();

        require __DIR__ . '/../views/salles.php';
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post()
    {
        $this->deconnexion();
        require __DIR__ . '/../views/salles.php';
    }

    /**
     * Fonction qui gère l'ajout d'une salle
     */
    public function ajouterSalle()
    {

    }
}
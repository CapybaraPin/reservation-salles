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
        global $db;

        $titre = 'Réservations';
        $colonnes = [
            "IDENTIFIANT_RESERVATION" => 'Identifiant',
            "DATE_DEBUT" => 'Date de début',
            "DATE_FIN" => 'Date de fin',
            "DESCRIPTION" => 'Description',
            "NOM_SALLE" => 'Salle',
            "TYPE_ACTIVITE" => 'Activité',
            "EMPLOYE" => 'Employé',
        ];

        $nbReservations = $db->getNbReservations();
        list($page, $pageMax) = $this->getPagination($nbReservations);
        $nbLignesPage = Config::get('NB_LIGNES');
        $reservations = $db->getReservations(($page - 1) * $nbLignesPage);

        // Création des actions pour chaque réservation
        // et ajout des informations demandées par les colonnes
        $actions = [];
        foreach ($reservations as &$reservation) {
            $reservation['ID'] = $reservation['IDENTIFIANT_RESERVATION'];
            $reservation['EMPLOYE'] = $reservation['PRENOM_EMPLOYE'] . ' ' . $reservation['NOM_EMPLOYE'];
            $dateDebut = date_create($reservation["DATE_DEBUT"]);
            $dateFin = date_create($reservation["DATE_FIN"]);
            $reservation["DATE_FIN"] = date_format($dateFin, "d/m/Y H\hi");
            $reservation["DATE_DEBUT"] = date_format($dateDebut, "d/m/Y H\hi");
            $actions[$reservation['IDENTIFIANT_RESERVATION']] = [
                'info' => ['attributs' => ['class' => 'btn btn-nav', 'title' => 'Plus d\'informations'], 'icone' => 'fa-solid fa-circle-info'],
            ];

            if ($_SESSION['userIndividuId'] == $reservation['ID_EMPLOYE']) {
                $actions[$reservation['IDENTIFIANT_RESERVATION']] += [
                    'modifier' => ['attributs' => ['class' => 'btn', 'title' => 'Modifier'], 'icone' => 'fa-solid fa-pen'],
                    'supprimer' => ['attributs' => ['class' => 'btn btn-nav', 'title' => 'Supprimer'], 'icone' => 'fa-solid fa-trash-can'],
                ];
            }
        }

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
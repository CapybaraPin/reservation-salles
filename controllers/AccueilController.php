<?php

namespace controllers;

use Exception;
use PDO;
use services\Config;

/**
 * Class AccueilController
 *
 * Ce contrôleur gère la page d'accueil de l'application.
 *
 * @package controllers
 */
class AccueilController extends ReservationsController
{

    public function get()
    {
        $titre = 'Mes Réservations';
        $colonnes = [
            "IDENTIFIANT_RESERVATION" => 'Identifiant',
            "DATE_DEBUT" => 'Date de début',
            "DATE_FIN" => 'Date de fin',
            "DESCRIPTION" => 'Description',
            "NOM_SALLE" => 'Salle',
            "TYPE_ACTIVITE" => 'Activité',
        ];

        $filtre["employe"][] = ['valeur' => $_SESSION['userIndividuId'], "type" => PDO::PARAM_INT, 'operateur' => "=", 'champ' => 'reservation.idEmploye'];
        $nbReservations = $this->reservationModel->getNbReservations($filtre);
        list($page, $pageMax) = $this->getPagination($nbReservations);
        $nbLignesPage = Config::get('NB_LIGNES');

        $reservations = $this->reservationModel->getReservations(($page - 1) * $nbLignesPage, $filtre);

        // Création des actions pour chaque réservation
        // et ajout des informations demandées par les colonnes
        $actions = [];
        foreach ($reservations as &$reservation) {
            $reservation['ID'] = $reservation['IDENTIFIANT_RESERVATION'];
            $dateDebut = date_create($reservation["DATE_DEBUT"]);
            $dateFin = date_create($reservation["DATE_FIN"]);
            $reservation["DATE_FIN"] = date_format($dateFin, "d/m/Y H\hi");
            $reservation["DATE_DEBUT"] = date_format($dateDebut, "d/m/Y H\hi");
            $actions[$reservation['IDENTIFIANT_RESERVATION']] = [
                'info' => ['attributs' => ['href' => '/reservations/'.$reservation["IDENTIFIANT_RESERVATION"].'/view',
                                           'class' => 'btn btn-nav', 'title' => 'Plus d\'informations'],
                                           'icone' => 'fa-solid fa-circle-info'],
                'modifier' => ['attributs' => ['class' => 'btn', 'title' => 'Modifier'], 'icone' => 'fa-solid fa-pen'],
                'supprimer' => ['attributs' => ['class' => 'btn btn-nav',
                                                'title' => 'SupprimerReservation',
                                                'href' => '#'.$reservation['ID']], 'icone' => 'fa-solid fa-trash-can'],

            ];

        }

        $erreur = $this->erreur;
        $success = $this->success;

        require __DIR__ . '/../views/accueil.php';
    }

    public function post()
    {
        // Vérifier si une demande de suppression est effectuée
        if (isset($_POST['supprimerReservation']) && isset($_POST['idReservation'])) {
            try {
                $this->supprimerReservation();
            } catch (Exception $e) {
                $this->erreur = "Erreur lors de la suppression de la reservation : " . $e->getMessage();
            }
        }

        $this->deconnexion();

        $this->get();
    }


}
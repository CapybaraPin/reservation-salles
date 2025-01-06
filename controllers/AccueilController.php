<?php

namespace controllers;

use PDO;
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

    private $success; // Pour gérer les messages de succès
    private $erreur;  // Pour gérer les messages d'erreur
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
                'info' => ['attributs' => ['class' => 'btn btn-nav', 'title' => 'Plus d\'informations'], 'icone' => 'fa-solid fa-circle-info'],
                'modifier' => ['attributs' => ['class' => 'btn', 'title' => 'Modifier'], 'icone' => 'fa-solid fa-pen'],
                'supprimer' => ['attributs' => ['class' => 'btn btn-nav', 'title' => 'SupprimerReservation', 'href' => '#'.$reservation['ID']], 'icone' => 'fa-solid fa-trash-can'],
            ];
        }

        if(isset($_SESSION['messageValidation'])) {
            $this->success = $_SESSION['messageValidation'];
            unset($_SESSION['messageValidation']);
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
            } catch (\Exception $e) {
                $this->erreur = "Erreur lors de la suppression de la reservation : " . $e->getMessage();
            }
        }

        $this->deconnexion();

        $this->get();
    }

    /**
     * Fonction qui gère la suppression d'une réservation.
     */
    private function supprimerReservation()
    {
        if(isset($_POST['idReservation']) && is_numeric($_POST['idReservation'])) {

            $id = intval($_POST['idReservation']);

            $res = $this->reservationModel->getReservation($id);

            if($res['ID_EMPLOYE'] == $_SESSION['userIndividuId']) {

                try {
                    $result = $this->reservationModel->supprimerReservation($id);


                    if ($result) {
                        $_SESSION['messageValidation'] =  "La réservation n°".$id." a bien été supprimée.";

                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit;

                    } else {
                        throw new \Exception("La suppression de la réservation a échoué. Veuillez réessayer.");
                    }

                } catch (\Exception $e) {
                    throw new \Exception("Une erreur s'est produite lors de la suppression de la réservation.");
                }

            } else {
                throw new \Exception("Données invalides. Veuillez vérifier les informations soumises.");
            }

        }
    }


}
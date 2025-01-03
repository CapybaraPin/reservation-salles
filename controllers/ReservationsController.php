<?php

namespace controllers;

use PDO;
use services\Auth;
use services\Config;

/**
 * Contrôleur pour la page des réservations
 */
class ReservationsController extends FiltresController
{

    private $success; // Pour gérer les messages de succès
    private $erreur;  // Pour gérer les messages d'erreur
    const FILTRES_DISPONIBLES = [
            'salle.nom' => ['label' => 'Salle', 'type' => PDO::PARAM_STR],
            'activite.type' => ['label' => 'Activité', 'type' => PDO::PARAM_STR],
            'reservation.dateDebut' => ['label' => 'Date de début', 'type' => PDO::PARAM_STR, 'operateur' => '>='],
            'reservation.dateFin' => ['label' => 'Date de fin', 'type' => PDO::PARAM_STR, 'operateur' => '<='],
            'individu.nom' => ['label' => 'Nom de l\'employé', 'type' => PDO::PARAM_STR],
            'individu.prenom' => ['label' => 'Prénom de l\'employé', 'type' => PDO::PARAM_STR],
        ];
    const FILTRE_DATE = ['reservation.dateDebut' => ['label' => 'Date de début', 'type' => PDO::PARAM_STR, 'operateur' => '>='],
                         'reservation.dateFin' => ['label' => 'Date de fin', 'type' => PDO::PARAM_STR, 'operateur' => '<=']];
    const TITRE = 'Réservations';

    const COLONNES = [
        "IDENTIFIANT_RESERVATION" => 'Identifiant',
        "DATE_DEBUT" => 'Date de début',
        "DATE_FIN" => 'Date de fin',
        "DESCRIPTION" => 'Description',
        "NOM_SALLE" => 'Salle',
        "TYPE_ACTIVITE" => 'Activité',
        "EMPLOYE" => 'Employé',
    ];

    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get()
    {
        $filtresDisponibles = self::FILTRES_DISPONIBLES;
        $this->setFiltresDisponibles($filtresDisponibles);
        $filtres = $this->getFiltres();


        // génération tableau
        $titre = self::TITRE;
        $colonnes = self::COLONNES;

        $filtresRequete = $this->getFiltresRequete();
        $nbReservations = $this->reservationModel->getNbReservations($filtresRequete);
        list($page, $pageMax) = $this->getPagination($nbReservations);

        $nbLignesPage = Config::get('NB_LIGNES');
        $reservations = $this->reservationModel->getReservations(($page - 1) * $nbLignesPage, $filtresRequete);

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

            //data-bs-toggle="modal" data-bs-target="#ajouterEmployee"
            if ($_SESSION['userIndividuId'] == $reservation['ID_EMPLOYE']) {
                $actions[$reservation['IDENTIFIANT_RESERVATION']] += [
                    'modifier' => ['attributs' => ['class' => 'btn', 'title' => 'Modifier'], 'icone' => 'fa-solid fa-pen'],
                    'supprimer' => ['attributs' => ['class' => 'btn btn-nav', 'title' => 'SupprimerReservation', 'href' => '#'.$reservation['ID']], 'icone' => 'fa-solid fa-trash-can'],
                ];
            }
        }

        if(isset($_SESSION['messageValidation'])) {
            $this->success = $_SESSION['messageValidation'];
            unset($_SESSION['messageValidation']);
        }

        $erreur = $this->erreur;
        $success = $this->success;

        require __DIR__ . '/../views/reservations.php';
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post()
    {
        $this->setFiltres($_POST['filtres'] ?? []);

        $filtresDisponibles = self::FILTRES_DISPONIBLES;
        $this->setFiltresDisponibles($filtresDisponibles);
        if (isset($_POST['ajouter_filtre'])) {
            $this->ajouterFiltre($_POST['nouveau_filtre']);
        } elseif (isset($_POST['supprimer_filtre'])) {
            $this->supprimerFiltre($_POST['supprimer_filtre']);
        }

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
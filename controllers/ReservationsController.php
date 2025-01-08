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
     * Fonction qui gère la consultation d'une salle
     * @param $salleId int Identifiant de la salle
     * @return void Affiche la page de consultation d'une
     */
    public function consultationReservation($reservationId)
    {
        $reservation = $this->reservationModel->getReservation($reservationId);
        try {
            if($reservation['ORGANISATION']!= NULL || !empty($reservation['ORGANISATION'])){
                $organisation =$this->reservationModel->getOrganisation($reservation['ORGANISATION']);
                $formateur = $this->employeModel->getindividu($organisation['idInterlocuteur']);
            }else{
                $formateur = $this->employeModel->getindividu($reservation['FORMATEUR']);
            }
        }catch (\Exception $e){
            $formateur = null;
        }
        require __DIR__ . '/../views/consultationReservation.php';
    }

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
                'info' => ['attributs' => ['href' => '/reservations/'.$reservation["IDENTIFIANT_RESERVATION"].'/view', 'class' => 'btn btn-nav', 'title' => 'Plus d\'informations'],
                    'icone' => 'fa-solid fa-circle-info'],
            ];

            if ($_SESSION['userIndividuId'] == $reservation['ID_EMPLOYE']) {
                $actions[$reservation['IDENTIFIANT_RESERVATION']] += [
                    'modifier' => ['attributs' => ['class' => 'btn', 'title' => 'Modifier'], 'icone' => 'fa-solid fa-pen'],
                    'supprimer' => ['attributs' => ['class' => 'btn btn-nav', 'title' => 'Supprimer'], 'icone' => 'fa-solid fa-trash-can'],
                ];
            }
        }

        $activites= $this->activiteModel->getActivites();
        $salles = $this->salleModel->getSalles();

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

        $this->deconnexion();
        $this->ajouterReservation();

        $erreurs = $this->erreurs;
        $success = $this->success;

        $this->get();
    }

    /**
     * Fonction qui gère l'ajout d'une réservation
     */
    public function ajouterReservation()
    {
        try {
            if (!empty($_POST['nomIntervenant']) || !empty($_POST['prenomIntervenant']) || !empty($_POST['telIntervenant'])) {
                $dateDebut = $_POST['dateDebut'];
                $dateFin = $_POST['dateFin'];
                $dateDebutAvecHeure = date('Y-m-d H:i:s', strtotime($dateDebut));
                $dateFinAvecHeure = date('Y-m-d H:i:s', strtotime($dateFin));
                $salle = $_POST['salle'];
                $activite = $_POST['typeReservation'];

                // Vérification des champs du formateur
                $nomFormateur = htmlspecialchars($_POST['nomIntervenant']);
                $prenomFormateur = htmlspecialchars($_POST['prenomIntervenant']);
                $telFormateur = htmlspecialchars($_POST['telIntervenant']);
            } else {
                $dateDebut = $_POST['dateDebut'];
                $dateFin = $_POST['dateFin'];
                $dateDebutAvecHeure = date('Y-m-d H:i:s', strtotime($dateDebut));
                $dateFinAvecHeure = date('Y-m-d H:i:s', strtotime($dateFin));
                $salle = $_POST['salle'];
                $activite = $_POST['typeReservation'];

                // Vérification des champs du formateur
                $nomFormateur = htmlspecialchars($_POST['nomIndividu']);
                $prenomFormateur = htmlspecialchars($_POST['prenomIndividu']);
                $telFormateur = htmlspecialchars($_POST['telIndividu']);
            }

            // Autres variables
            $employe = $_SESSION['userIndividuId'];
            $nomOrganisation = htmlspecialchars($_POST['nomOrganisation']);
            $description = htmlspecialchars($_POST['description']);

            // Ajout de la réservation
            $this->reservationModel->ajouterReservation(
                $dateDebutAvecHeure,
                $dateFinAvecHeure,
                $salle,
                $activite,
                $nomFormateur,
                $prenomFormateur,
                $telFormateur,
                $employe,
                $nomOrganisation,
                $description
            );
        } catch (FieldValidationException $e) {
            // Gérer les erreurs de validation des champs sans afficher sur la page
            $this->erreurs = $e->getErreurs();
            // Vous pouvez enregistrer l'erreur dans un fichier de log si nécessaire
            error_log("Erreur de validation : " . implode(", ", $this->erreurs));
        } catch (\Exception $e) {
            // Gérer les erreurs générales sans afficher sur la page
            // Optionnel: Enregistrer l'erreur dans un fichier de log
            error_log("Erreur lors de l'ajout de la réservation : " . $e->getMessage());

            // Définir un message d'erreur générique
            $_SESSION['messageErreur'] = 'Une erreur est survenue, veuillez réessayer plus tard.';
        }
    }




}
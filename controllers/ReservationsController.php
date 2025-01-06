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
            $formateur = $this->employeModel->getindividu($reservation['FORMATEUR']);
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

        $this->get();
        }


    /**
     * Fonction qui gère l'ajout d'une réservation
     */
    public function ajouterReservation()
    {
        // Récupération des données du formulaire
        if (isset($_POST['nom']) && isset($_POST['capacite']) && isset($_POST['typeOrdinateur'])) {

            $dateDebut = htmlspecialchars($_POST['dateDebut']);
            $dateFin = (int)htmlspecialchars($_POST['capacite']);
            $videoProjecteur = isset($_POST['videoProjecteur']) ? 1 : 0;
            $nbOrdinateurs = isset($_POST['nbOrdinateurs']) && is_numeric($_POST['nbOrdinateurs']) ? (int)$_POST['nbOrdinateurs'] : 0;
            $logiciels = isset($_POST['logiciels']) && is_array($_POST['logiciels']) ? $_POST['logiciels'] : [];
            $imprimante = isset($_POST['imprimante']) ? 1 : 0;
            $typeOrdinateur = htmlspecialchars($_POST['typeOrdinateur']);
            $ecranXXL = isset($_POST['ecranXXL']) ? 1 : 0;

            // Ajout du groupe d'ordinateurs
            $idGroupeOrdinateur = $this->ordinateurModel->ajouterGroupeOrdinateur($nbOrdinateurs, $imprimante, $typeOrdinateur);

            // Ajout des logiciels si présents
            foreach ($logiciels as $logiciel) {
                if (!empty($logiciel) && $logiciel != -1) {
                    $this->ordinateurModel->ajouterLogiciel($idGroupeOrdinateur, htmlspecialchars($logiciel));
                }
            }

            // Ajout de la salle
            try {
                $this->salleModel->ajouterSalle($nom, $capacite, $videoProjecteur, $ecranXXL, $idGroupeOrdinateur);
            } catch (FieldValidationException $e) {
                $this->erreurs = $e->getErreurs();
            }
        }
    }



}
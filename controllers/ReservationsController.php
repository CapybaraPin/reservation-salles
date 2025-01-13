<?php

namespace controllers;

use PDO;
use services\Auth;
use services\Config;
use services\exceptions\FieldValidationException;

/**
 * Contrôleur pour la page des réservations
 */
class ReservationsController extends FiltresController
{

    protected $erreurs; // Pour gérer les messages d'erreur
    protected $erreur;  // Pour gérer les messages d'erreur
    protected $success; // Pour gérer les messages de succès

    const FILTRES_DISPONIBLES = [
        'salle' => ['label' => 'Salle', 'type' => PDO::PARAM_STR, 'champ' => 'salle.nom'],
        'type' => ['label' => 'Activité', 'type' => PDO::PARAM_STR, 'champ' => 'activite.type'],
        'date' => ['label' => 'Date', 'type' => PDO::PARAM_STR, 'input' => 'date', 'champ' => 'reservation.dateDebut, reservation.dateFin'],
        'periode' => ['label' => 'Periode', 'type' => PDO::PARAM_STR, 'input' => 'datetime-local', 'champ' => 'reservation.dateDebut, reservation.dateFin'],
        'nom' => ['label' => 'Nom de l\'employé', 'type' => PDO::PARAM_STR, 'champ' => 'individu.nom'],
        'prenom' => ['label' => 'Prénom de l\'employé', 'type' => PDO::PARAM_STR, 'champ' => 'individu.prenom'],
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
                'info' => ['attributs' => ['href' => '/reservations/'.$reservation["IDENTIFIANT_RESERVATION"].'/view', 'class' => 'btn btn-nav', 'title' => 'Plus d\'informations'],
                    'icone' => 'fa-solid fa-circle-info'],
            ];

            //data-bs-toggle="modal" data-bs-target="#ajouterEmployee"
            if ($_SESSION['userIndividuId'] == $reservation['ID_EMPLOYE']) {
                $actions[$reservation['IDENTIFIANT_RESERVATION']] += [
                    'modifier' => ['attributs' => ['href' => '/reservations/'.$reservation["IDENTIFIANT_RESERVATION"].'/edit', 'class' => 'btn btn-nav', 'title' => 'Modifier'],
                        'icone' => 'fa-solid fa-pen'],
                    'supprimer' => ['attributs' => ['class' => 'btn btn-nav', 'title' => 'SupprimerReservation', 'href' => '#'.$reservation['ID']], 'icone' => 'fa-solid fa-trash-can'],
                ];
            }
        }

        $activites= $this->activiteModel->getActivites();
        $salles = $this->salleModel->getSalles();

        $erreurs = $this->erreurs;
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

        if(isset($_POST['ajouterReservation'])){
            $this->ajouterReservation();
        }

        $erreurs = $this->erreurs;
        $success = $this->success ;

        $this->get();
    }

    /**
     * Fonction qui gère la consultation d'une salle
     * @param $salleId int Identifiant de la salle
     * @return void Affiche la page de consultation d'une
     */
    public function consultationReservation($reservationId)
    {
        $reservation = $this->reservationModel->getReservation($reservationId);
        $dateDebut = date_create($reservation["DATE_DEBUT"]);
        $dateFin = date_create($reservation["DATE_FIN"]);
        $date = date_format($dateDebut, "d/m/Y");
        $formatDate = date_format($dateDebut, "l, j F Y");
        $heureDebut = date_format($dateDebut, "H\hi");
        $heureFin = date_format($dateFin, "H\hi");
        $search = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $replace = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

        $formatDate = str_replace($search, $replace, $formatDate);
        try {
            if($reservation['IDENTIFIANT_ORGANISATION']!= NULL || !empty($reservation['IDENTIFIANT_ORGANISATION'])){
                $organisation =$this->reservationModel->getOrganisation($reservation['IDENTIFIANT_ORGANISATION']);
                $formateur = $this->employeModel->getindividu($organisation['idInterlocuteur']);
            }else{
                $formateur = $this->employeModel->getindividu($reservation['IDENTIFIANT_FORMATEUR']);
            }
        }catch (\Exception $e){
            $formateur = null;
        }
        require __DIR__ . '/../views/consultationReservation.php';
    }

    /**
     * Fonction qui gère l'ajout d'une réservation
     */
    protected function ajouterReservation()
    {
        try {
            // Initialisation des variables communes
            $dateDebut = htmlspecialchars($_POST['dateDebut']);
            $dateFin = htmlspecialchars($_POST['dateFin']);
            $dateDebutAvecHeure = date('Y-m-d H:i:s', strtotime($dateDebut));
            $dateFinAvecHeure = date('Y-m-d H:i:s', strtotime($dateFin));
            $salle = htmlspecialchars($_POST['salle']);
            $activite = htmlspecialchars($_POST['typeReservation']);

            // Déterminer les champs du formateur
            if (!empty($_POST['nomIntervenant']) || !empty($_POST['prenomIntervenant']) || !empty($_POST['telIntervenant'])) {
                $nomIntervenant = htmlspecialchars($_POST['nomIntervenant']);
                $prenomIntervenant = htmlspecialchars($_POST['prenomIntervenant']);
                $telIntervenant = htmlspecialchars($_POST['telIntervenant']);
            } else {
                $nomIntervenant = htmlspecialchars($_POST['nomIndividu']);
                $prenomIntervenant = htmlspecialchars($_POST['prenomIndividu']);
                $telIntervenant = htmlspecialchars($_POST['telIndividu']);
            }

            // Autres variables
            $employe = $_SESSION['userIndividuId'];

            $nomOrganisation = htmlspecialchars($_POST['nomOrganisation']);
            $description = !empty($_POST['sujetLocation']) ? htmlspecialchars($_POST['sujetLocation']) :
                           (!empty($_POST['sujetFormation']) ? htmlspecialchars($_POST['sujetFormation']) : htmlspecialchars($_POST['description']));

            // Ajout de la réservation
            $this->reservationModel->ajouterReservation(
                $dateDebutAvecHeure,
                $dateFinAvecHeure,
                $salle,
                $activite,
                $nomIntervenant,
                $prenomIntervenant,
                $telIntervenant,
                $employe,
                $nomOrganisation,
                $description
            );

            // Définir le message de succès
            $this->success = 'La réservation a été ajoutée avec succès.';
        } catch (FieldValidationException $e) {
            $this->erreurs = $e->getErreurs();
        } catch (\Exception $e) {
            $this->erreur = 'Une erreur est survenue, veuillez réessayer plus tard.';
        }
    }


    /**
     * Fonction qui gère la suppression d'une réservation.
     */
    protected function supprimerReservation()
    {
        if(isset($_POST['idReservation']) && is_numeric($_POST['idReservation'])) {

            $id = intval($_POST['idReservation']);

            $res = $this->reservationModel->getReservation($id);
            if (!$res || $res['ID_EMPLOYE'] != $_SESSION['userIndividuId']) {
                throw new \Exception("Données invalides. Veuillez vérifier les informations soumises.");
            }

            try {
                $result = $this->reservationModel->supprimerReservation($id);

                if (!$result) {
                    throw new \Exception("La suppression de la réservation a échoué. Veuillez réessayer.");
                }

                $this->success = "La réservation n°" . $id . " a bien été supprimée.";
            } catch (\Exception $e) {
                throw new \Exception("Une erreur s'est produite lors de la suppression de la réservation.");
            }

        }
    }

    /**
     * Fonction qui gère la modification d'une réservation
     * @param $reservationId int Identifiant de la réservation
     */
    public function modificationReservation($reservationId)
    {
        // Récupération des informations de la réservation
        $reservation = $this->reservationModel->getReservation($reservationId);
        $activites= $this->activiteModel->getActivites();
        $salles = $this->salleModel->getSalles();
        $organismes = $this->organismeModel->getOrganismes();

        try {
            if($reservation['IDENTIFIANT_ORGANISATION']!= NULL || !empty($reservation['IDENTIFIANT_ORGANISATION'])){
                $organisation =$this->reservationModel->getOrganisation($reservation['IDENTIFIANT_ORGANISATION']);
                $formateur = $this->employeModel->getindividu($organisation['idInterlocuteur']);
            }else{
                $formateur = $this->employeModel->getindividu($reservation['IDENTIFIANT_FORMATEUR']);
            }
        }catch (\Exception $e){
            $formateur = null;
        }

        if (isset($_POST['modifierReservation'])) {
            // Récupération des informations du formulaire
            $dateDebut = htmlspecialchars($_POST['dateDebut']);
            $dateFin = htmlspecialchars($_POST['dateFin']);
            $heureDebut = htmlspecialchars($_POST['heureDebut']);
            $heureFin = htmlspecialchars($_POST['heureFin']);

            $idTypeActivite = htmlspecialchars($_POST['typeActivite']);
            $salleId = htmlspecialchars($_POST['salle']);

            if ($idTypeActivite == 1 || $idTypeActivite == 3 || $idTypeActivite == 6) {
                $description = htmlspecialchars($_POST['description']);
            } elseif ($idTypeActivite == 4 || $idTypeActivite == 5) {
                $description = htmlspecialchars($_POST['sujetFormation']);
            } elseif ($idTypeActivite == 2) {
                $description = htmlspecialchars($_POST['sujetLocation']);
            }
        }
        require __DIR__ . '/../views/modifierReservation.php';
    }
}
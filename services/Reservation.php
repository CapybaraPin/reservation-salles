<?php

namespace services;

use PDO;
use services\SQLHelper;

class Reservation
{
    /**
     * Récupère le nombre total de réservations
     * @return mixed Retourne le nombre total de réservations
     */
    public function getNbReservations($filtre = [])
    {
        $pdo = Database::getPDO();

        $sql = "SELECT COUNT(*) 
                FROM reservation
                JOIN salle 
                ON salle.identifiant = reservation.idSalle
                JOIN activite
                ON activite.identifiant = reservation.idActivite
                JOIN individu
                ON individu.identifiant = reservation.idEmploye";

        // Ajout des filtres
        $sql .= SQLHelper::construireConditionsFiltres($filtre);

        $req = $pdo->prepare($sql);
        // Liaison des paramètres avec leurs valeurs et types
        SQLHelper::bindValues($req, $filtre);

        $req->execute();
        return $req->fetchColumn();
    }

    /**
     * Récupère la liste des réservations en fonction des filtres
     * passés en paramètre exemple du contenu de $filtre :
     * ['reservation.dateDebut' => ['2021-10-01', PDO::PARAM_STR], ETC...]
     *
     * @param array $filtre Filtres de recherche
     * @return mixed Retourne la liste des réservations
     */
    public function getReservations($offset = 0, $filtre = [], $limit = null)
    {
        $pdo = Database::getPDO();

        if (is_null($limit)) {
            $limit = Config::get('NB_LIGNES');
        }

        $sql = "SELECT
                    reservation.identifiant AS 'IDENTIFIANT_RESERVATION',
                    reservation.dateDebut AS 'DATE_DEBUT',
                    reservation.dateFin AS 'DATE_FIN',
                    reservation.description AS 'DESCRIPTION',
                    salle.nom AS 'NOM_SALLE',
                    activite.type AS 'TYPE_ACTIVITE',
                    individu.prenom AS 'PRENOM_EMPLOYE',
                    individu.nom AS 'NOM_EMPLOYE',
                    individu.identifiant AS 'ID_EMPLOYE'
                FROM reservation
                JOIN salle 
                ON salle.identifiant = reservation.idSalle
                JOIN activite
                ON activite.identifiant = reservation.idActivite
                JOIN individu
                ON individu.identifiant = reservation.idEmploye";

        // Ajout des filtres
        $sql .= SQLHelper::construireConditionsFiltres($filtre);

        $sql .= " ORDER BY reservation.identifiant ASC LIMIT :limit OFFSET :offset";

        $req = $pdo->prepare($sql);
        $req->bindParam(':limit', $limit, PDO::PARAM_INT);
        $req->bindParam(':offset', $offset, PDO::PARAM_INT);
        // Liaison des paramètres avec leurs valeurs et types
        // Liaison des paramètres avec types
        SQLHelper::bindValues($req, $filtre);

        $req->execute();
        return $req->fetchAll();
    }

    /**
     * Permet de récupérer le nombre de réservations d'une salle
     * @param $idSalle int l'identifiant de la salle
     * @return mixed, Retourne le nombre de réservations d'une salle
     */
    public function getNbReservationsSalle($idSalle)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("SELECT COUNT(*) 
                                FROM reservation 
                                WHERE idSalle = ?");
        $req->execute([$idSalle]);

        return $req->fetchColumn();
    }

    /**
     * Permet de récuperer une réservation dans la base de données.
     *
     * @param $id int, L'identifiant de la réservation à récuperer
     * @return mixed, Retourne la réservation obtenue
     */
    public function getReservation($idReservation) {
        global $pdo;

        $req = $pdo->prepare("SELECT reservation.identifiant as IDENTIFIANT_RESERVATION, dateDebut, dateFin, description, organisme.nomOrganisme AS NOM_ORGANISME,activite.type AS ACTIVITE, salle.nom AS NOM_SALLE, individu.nom AS NOM_EMPLOYE, individu.prenom AS PRENOM_EMPLOYE, reservation.idFormateur AS FORMATEUR    
                                    FROM reservation 
                                    JOIN organisme 
                                    ON reservation.idOrganisation = organisme.identifiant 
                                    JOIN activite 
                                    ON reservation.idActivite = activite.identifiant 
                                    JOIN salle 
                                    ON reservation.idSalle = salle.identifiant
                                    JOIN individu 
                                    ON reservation.idEmploye = individu.identifiant
                                    WHERE reservation.identifiant = :id");
        $req->execute(['id' => $idReservation]);

        return $req->fetch();
    }

    /**
     * Permet d'ajouter une réservation dans la base de données
     * @param $dateDebut date date de début de la réservation
     * @param $dateFin date date de fin de réservation
     * @param $salle int identifiant de la salle concernée par la réservation
     * @param $activite int identifiant de l'activité de la réservation
     * @param $formateur int identifiant du formateur s'il y en a un
     * @param $employe int identifiant de l'employé qui a effectué cette réservation
     * @param $organisation int identifiant de l'organisation qui effectue la réservation
     * @param $description string description de l'activité effectuée lors de la réservation
     */
    public function ajouterReservation($dateDebut, $dateFin, $salle, $activite, $formateur, $employe, $organisation, $description)
    {
        $pdo = Database::getPDO();

        // Validation des paramètres
        $erreurs = [];

        if (empty($dateDebut) || empty($dateFin)) {
            $erreurs["dates"] = "Les dates de début et de fin sont requises.";
        } elseif (strtotime($dateDebut) >= strtotime($dateFin)) {
            $erreurs["dates"] = "La date de début doit être antérieure à la date de fin.";
        } elseif (strtotime($dateDebut) < strtotime(date('Y-m-d'))) {
            $erreurs["dates"] = "La date de début ne peut pas être inférieure à aujourd'hui.";
        }

        if (empty($salle) || !is_numeric($salle)) {
            $erreurs["salle"] = "Un identifiant de salle valide est requis.";
        }

        if (empty($activite) || !is_numeric($activite)) {
            $erreurs["activite"] = "Un identifiant d'activité valide est requis.";
        }
        

        if (empty($employe) || !is_numeric($employe)) {
            $erreurs["employe"] = "Un identifiant d'employé valide est requis.";
        }

        if (!empty($erreurs)) {
            throw new \Exception(json_encode($erreurs));
        }

        // Insertion de la réservation
        $req = $pdo->prepare(
            "INSERT INTO reservations (dateDebut, dateFin, salle, activite, formateur, employe, organisation, description) 
        VALUES (:dateDebut, :dateFin, :salle, :activite, :formateur, :employe, :organisation, :description)"
        );

        $req->execute([
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'salle' => $salle,
            'activite' => $activite,
            'formateur' => $formateur,
            'employe' => $employe,
            'organisation' => $organisation,
            'description' => $description
        ]);
    }

}
<?php

namespace services;

use PDO;
use services\exceptions\FieldValidationException;
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
     * Fonction permettant de récupérer les informations d'une réservation.
     * @param $idReservation L'identifiant de la réservation.
     * @return mixed Retourne toutes les informations de la réservation.
     */
    public function getReservation($idReservation) {

        $pdo = Database::getPDO();

        $sql = "SELECT
                    reservation.identifiant AS 'IDENTIFIANT_RESERVATION',
                    reservation.dateDebut AS 'DATE_DEBUT',
                    reservation.dateFin AS 'DATE_FIN',
                    reservation.description AS 'DESCRIPTION',
                    reservation.idOrganisation AS 'IDENTIFIANT_ORGANISATION',
                    reservation.idFormateur AS 'IDENTIFIANT_FORMATEUR',
                    reservation.idActivite AS 'IDENTIFIANT_ACTIVITE',
                    reservation.idSalle AS 'IDENTIFIANT_SALLE',
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
                ON individu.identifiant = reservation.idEmploye 
                WHERE reservation.identifiant = ?";

        $req = $pdo->prepare($sql);
        $req->execute([$idReservation]);

        return $req->fetch();

    }

    /**
     * Fonction permettant la suppression d'une réservation.
     * @param $idReservation L'identifiant de la réservation à supprimer.
     * @return bool Retourne si la suppression a bien été effectuée.
     */
    public function supprimerReservation($idReservation) {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("DELETE FROM reservation WHERE identifiant = ?");
        $resultat = $req->execute([$idReservation]);

        return $resultat;
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
     * @return mixed Retourne les champs nécessaires pour l'exportation des données
     */
    public function getReservationsExport()
    {
        $pdo = Database::getPDO();

        $sql = "SELECT
                    reservation.identifiant AS 'IDENTIFIANT_RESERVATION',
                    reservation.dateDebut AS 'DATE_DEBUT',
                    reservation.dateFin AS 'DATE_FIN',
                    reservation.description AS 'DESCRIPTION',
                    idSalle AS 'IDENTIFIANT_SALLE',
                    activite.type AS 'TYPE_ACTIVITE',
                    idEmploye AS 'IDENTIFIANT_EMPLOYE',
                    individu.nom AS 'NOM_FORMATEUR',
                    individu.prenom AS 'PRENOM_FORMATEUR',
                    individu.telephone AS 'TELEPHONE_FORMATEUR',
                    organisme.nomOrganisme AS 'NOM_ORGANISME',
                    idOrganisation AS 'IDENTIFIANT_ORGANISME'
                FROM reservation
                JOIN activite
                ON activite.identifiant = reservation.idActivite
                LEFT JOIN individu
                ON individu.identifiant = reservation.idFormateur
                LEFT JOIN organisme
                ON organisme.identifiant = reservation.idOrganisation";

        $req = $pdo->prepare($sql);

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
     * @param $idOrganisme int, L'identifiant de l'organisation à récuperer
     * @return mixed, Retourne l'organisation obtenue
     */
    public function getOrganisation($idOrganisme)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare(
            "SELECT identifiant, nomOrganisme, idInterlocuteur
                    FROM organisme
                    WHERE identifiant = :id"
        );

        $req->execute(['id' => $idOrganisme]);

        return $req->fetch();
    }

    /**
     * Permet d'ajouter une réservation dans la base de données
     * @param $dateDebut date date de début de la réservation
     * @param $dateFin date date de fin de réservation
     * @param $salle int identifiant de la salle concernée par la réservation
     * @param $activite int identifiant de l'activité de la réservation
     * @param $nomIntervenant string nom du formateur s'il y en a un
     * @param $prenomIntervenant string prenom du formateur s'il y en a un
     * @param $telIntervenant int telephone du formateur s'il y en a un
     * @param $employe int identifiant de l'employé qui a effectué cette réservation
     * @param $nomOrganisation string nom de l'organisation s'il y en a un
     * @param $description string description de l'activité effectuée lors de la réservation
     */
    public function ajouterReservation($dateDebut, $dateFin, $salle, $activite, $nomIntervenant, $prenomIntervenant, $telIntervenant, $employe, $nomOrganisation, $description)
    {
        $pdo = Database::getPDO();

        $erreurs = [];

        // Validation des dates
        $timestampDateDebut = strtotime($dateDebut);
        $timestampDateFin = strtotime($dateFin);
        $datesValides = $this->validerDates($timestampDateDebut, $timestampDateFin);
        if (!$datesValides) {
            array_merge($erreurs, $datesValides);
        }

        // Validation des autres champs obligatoires
        if (empty($salle) || !is_numeric($salle) || $salle == 0) {
            $erreurs["Salle"] = "Veillez choisir une salle.";
        }

        if (empty($activite) || !is_numeric($activite) || $activite == 0) {
            $erreurs["Activite"] = "Un identifiant d'activité valide est requis.";
        }

        if (empty($employe) || !is_numeric($employe)) {
            $erreurs["Employe"] = "Un identifiant d'employé valide est requis.";
        }

        // Gestion de l'organisation ou du formateur
        $organisationId = null;
        $formateur = null;

        // si ajout d'une organisation sinon ajout d'un formateur
        if (!empty(trim($nomOrganisation))) {
            $organisationID = Organisme::getIdOrganisation($nomOrganisation);
            if (!$organisationID) {
                // Gestion de l'interlocuteur (individu) pour l'organisation
                $interlocuteur = null;
                if (!empty(trim($nomIntervenant)) && !empty(trim($prenomIntervenant)) && !empty(trim($telIntervenant))) {
                    $interlocuteurId = Organisme::getIdInterlocuteur($nomIntervenant, $prenomIntervenant, $telIntervenant);
                    if (!$interlocuteurId) {
                        $interlocuteurId = Organisme::ajouterInterlocuteur($nomIntervenant, $prenomIntervenant, $telIntervenant);
                    }
                }

                $organisationId = Organisme::ajouterOrganisation($nomOrganisation, $interlocuteurId);
            }
        } elseif (!empty(trim($nomIntervenant)) && !empty(trim($prenomIntervenant)) && !empty(trim($telIntervenant))) {
            // Gestion du formateur
            $reqVerifIndividu = $pdo->prepare(
                "SELECT identifiant FROM individu WHERE nom = :nom AND prenom = :prenom AND telephone = :telephone"
            );
            $reqVerifIndividu->execute([
                'nom' => trim($nomIntervenant),
                'prenom' => trim($prenomIntervenant),
                'telephone' => trim($telIntervenant)
            ]);
            $formateurExiste = $reqVerifIndividu->fetchColumn();

            if (!$formateurExiste) {
                // Insérer un nouvel individu
                $reqInsertIndividu = $pdo->prepare(
                    "INSERT INTO individu (nom, prenom, telephone) VALUES (:nom, :prenom, :telephone)"
                );
                $reqInsertIndividu->execute([
                    'nom' => trim($nomIntervenant),
                    'prenom' => trim($prenomIntervenant),
                    'telephone' => trim($telIntervenant)
                ]);
                $formateur = $pdo->lastInsertId();
            } else {
                $formateur = $formateurExiste;
            }
        }

        // Vérification de l'existence d'une réservation
        $reqVerifReservation = $pdo->prepare(
            "SELECT identifiant FROM reservation WHERE idSalle = :salle AND 
         (:dateDebut BETWEEN dateDebut AND dateFin OR :dateFin BETWEEN dateDebut AND dateFin)"
        );
        $reqVerifReservation->execute([
            'salle' => $salle,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin
        ]);
        $reservationExiste = $reqVerifReservation->fetchColumn();

        if ($reservationExiste) {
            $erreurs["reservation"] = "Une réservation existe déjà pour cette salle à ces dates.";
        }

        if (!empty($erreurs)) {
            throw new FieldValidationException($erreurs);
        }

        // Insertion de la réservation
        $req = $pdo->prepare(
            "INSERT INTO reservation (dateDebut, dateFin, idSalle, idActivite, idFormateur, idEmploye, idOrganisation, description) 
     VALUES (:dateDebut, :dateFin, :salle, :activite, :formateur, :employe, :organisation, :description)"
        );
        $req->bindValue(':dateDebut', $dateDebut);
        $req->bindValue(':dateFin', $dateFin);
        $req->bindValue(':salle', $salle, PDO::PARAM_INT);
        $req->bindValue(':activite', $activite, PDO::PARAM_INT);
        $req->bindValue(':formateur', $formateur, $formateur === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $req->bindValue(':employe', $employe, PDO::PARAM_INT);
        $req->bindValue(':organisation', $organisationId, $organisationId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $req->bindValue(':description', $description);
        $req->execute();
    }

    /**
     * Permet de valider les dates de début et de fin d'une réservation
     * @param $timestampDateDebut int, Le timestamp de la date de début
     * @param $timestampDateFin int, Le timestamp de la date de fin
     * @return bool|array, Retourne true si les dates sont valides, sinon un tableau d'erreurs
     */
    private function validerDates($timestampDateDebut, $timestampDateFin)
    {
        $heureMin = Config::get('HEURE_OUVERTURE');
        $heureMax = Config::get('HEURE_FERMETURE');
        $jourDebut = date('Y-m-d', $timestampDateDebut);
        $jourFin = date('Y-m-d', $timestampDateFin);
        $heureDebut = date('H:i:s', $timestampDateDebut);
        $heureFin = date('H:i:s', $timestampDateFin);

        $erreur = [];
        if (empty($dateDebut) || empty($dateFin)) {
            $erreur["Dates"] = "Les dates de début et de fin sont requises.";
        } elseif ($timestampDateDebut >= $timestampDateFin) {
            $erreur["Dates"] = "La date de début doit être antérieure à la date de fin.";
        } elseif ($timestampDateDebut < strtotime(date('Y-m-d'))) {
            $erreur["Dates"] = "La date de début ne peut pas être inférieure à aujourd'hui.";
        } elseif ($jourDebut !== $jourFin) {
            $erreur["Dates"] = "Les réservations doivent être sur une seule journée.";
        } elseif ($heureDebut < $heureMin || $heureDebut > $heureMax || $heureFin < $heureMin || $heureFin > $heureMax) {
            $erreur["Horaire"] = "Les heures doivent être comprises entre " . $heureMin . " et " . $heureMax . ".";
        }

        return $erreur ? true : $erreur;
    }
}
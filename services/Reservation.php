<?php

namespace services;

use Exception;
use PDO;
use PDOException;
use services\exceptions\FieldValidationException;
use services\SQLHelper;

/**
 * Classe pour les réservations
 */
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
                    reservation.idEmploye AS 'IDENTIFIANT_EMPLOYE',
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
     * Permet de savoir si une réservation existe déjà pour une salle à ces dates
     *
     * @param $idSalle int, L'identifiant de la salle
     * @param $dateDebut date, La date de début de la réservation
     * @param $dateFin date, La date de fin de la réservation
     * @return bool, Retourne true si une réservation existe déjà, sinon false
     */
    public function reservationExiste($idSalle, $dateDebut, $dateFin)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare(
            "SELECT COUNT(*) 
                    FROM reservation 
                    WHERE idSalle = :idSalle 
                    AND dateDebut = :dateDebut 
                    AND dateFin = :dateFin"
        );

        $req->execute([
            'idSalle' => $idSalle,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin
        ]);

        return $req->fetchColumn() > 0;
    }

    /**
     * Permet d'ajouter une réservation dans la base de données
     * @param $dateDebut date date de début de la réservation
     * @param $dateFin date date de fin de réservation
     * @param $salle int identifiant de la salle concernée par la réservation
     * @param $activite int identifiant de l'activité de la réservation
     * @param $idIntervenant int identifiant du formateur s'il y en a un
     * @param $nomIntervenant string nom du formateur s'il y en a un
     * @param $prenomIntervenant string prenom du formateur s'il y en a un
     * @param $telIntervenant int telephone du formateur s'il y en a un
     * @param $employe int identifiant de l'employé qui a effectué cette réservation
     * @param $nomOrganisation string nom de l'organisation s'il y en a un
     * @param $description string description de l'activité effectuée lors de la réservation
     *
     * @throws FieldValidationException si une erreur survient lors de la validation des champs
     * @throws Exception
     */
    public function ajouterReservation($dateDebut, $dateFin, $salle, $activite, $idIntervenant, $nomIntervenant, $prenomIntervenant, $telIntervenant, $employe, $idOrganisation, $nomOrganisation, $description)
    {
        $pdo = Database::getPDO();
        $erreurs = [];

        // Validation des dates
        $timestampDateDebut = strtotime($dateDebut);
        $timestampDateFin = strtotime($dateFin);
        $datesValides = $this->validerDates($timestampDateDebut, $timestampDateFin);
        if (!$datesValides) {
            $erreurs = array_merge($erreurs, $datesValides);
        }

        // Validation des autres champs obligatoires
        if (empty($salle) || !is_numeric($salle) || $salle == 0) {
            $erreurs["Salle"] = "Veillez choisir une salle.";
        }

        if (empty($activite) || !is_numeric($activite) || $activite == 0) {
            $erreurs["Activite"] = "Un identifiant d'activité valide est requis.";
        }

        if (empty($employe) || !is_numeric($employe)) {
            $erreurs["Individu"] = "Un identifiant d'employé valide est requis.";
        }

        // Vérification de l'existence d'une réservation pour cette salle à ces dates
        if ($this->reservationExiste($salle, $dateDebut, $dateFin)) {
            $erreurs["Reservation"] = "Une réservation existe déjà pour cette salle à ces dates.";
        }


        $organismeModel = new Organisme();
        $employeModel = new Individu();
        if ($activite == 2 && !$employeModel->individuExiste($idIntervenant)) {
            if(empty($idIntervenant) && empty($nomIntervenant) && empty($prenomIntervenant) && empty($telIntervenant)) {
                $erreurs["Formateur"] = "Veuillez renseigner tous les champs du formateur.";
            }
            if($telIntervenant != null) {
                if (!preg_match('/^\d{4}$/', $telIntervenant)) {
                    $erreurs['telephone'] = "Le numéro de téléphone est invalide.";
                }
            }
        } else if (($activite == 4 or $activite == 5) && !$organismeModel->organismeExiste($idOrganisation)) {
            if(empty($idIntervenant) && empty($nomIntervenant) && empty($prenomIntervenant) && empty($telIntervenant)) {
                $erreurs["Organisme"] = "Veuillez renseigner tous les champs de l'intervenant.";
            }
            if(empty($idOrganisation) && empty($nomOrganisation)) {
                $erreurs["Organisme"] = "Veuillez renseigner tous les champs de l'organisation.";
            }
            if($telIntervenant != null) {
                if (!preg_match('/^\d{4}$/', $telIntervenant)) {
                    $erreurs['telephone'] = "Le numéro de téléphone est invalide.";
                }
            }
        }

        if (!empty($erreurs)) {
            throw new FieldValidationException($erreurs);
        }

        // Gestion de l'organisation ou du formateur
        $organisationId = null;
        $formateurId = null;

        $nomOrganisation = trim($nomOrganisation);
        $nomIntervenant = trim($nomIntervenant);
        $prenomIntervenant = trim($prenomIntervenant);
        $telIntervenant = trim($telIntervenant);

        // si ajout d'une organisation sinon ajout d'un formateur
        try {
            $pdo->beginTransaction();
            if ($organismeModel->organismeExiste($idOrganisation)) {
                $organisationId = $idOrganisation;
            } else if (!empty($nomOrganisation)) {
                $organisationID = $organismeModel->getIdOrganisme($nomOrganisation);
                if (!$organisationID) {
                    // Gestion de l'interlocuteur (individu) pour l'organisation
                    $interlocuteurId = null;
                    if (!empty($nomIntervenant) && !empty($prenomIntervenant) && !empty($telIntervenant)) {
                        $interlocuteurId = $organismeModel->getIdInterlocuteur($nomIntervenant, $prenomIntervenant, $telIntervenant);
                        if (!$interlocuteurId) {
                            $interlocuteurId = $organismeModel->ajouterInterlocuteur($nomIntervenant, $prenomIntervenant, $telIntervenant);
                        }
                    }

                    $organisationId = $organismeModel->ajouterOrganisme($nomOrganisation, $interlocuteurId);
                }
            } elseif($employeModel->individuExiste($idIntervenant)) {
                if ($idIntervenant != $employe) {
                    $formateurId = $idIntervenant;
                }
            } elseif (!empty($nomIntervenant) && !empty($prenomIntervenant) && !empty($telIntervenant)) {
                $formateurId = $employeModel->ajouterIndividu($nomIntervenant, $prenomIntervenant, $telIntervenant);
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
            $req->bindValue(':formateur', $formateurId, $formateurId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $req->bindValue(':employe', $employe, PDO::PARAM_INT);
            $req->bindValue(':organisation', $organisationId, $organisationId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $req->bindValue(':description', $description);
            $req->execute();

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw new Exception($e->getMessage());
        }

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

    public function modifierReservation($idReservation, $dateDebut, $dateFin, $heureDebut, $heureFin, $idTypeActivite, $salleId, $idEmploye, $description)
    {
        $pdo = Database::getPDO();
        $erreurs = [];

        $dateHeureDebut = strtotime($dateDebut . ' ' . $heureDebut);
        $dateHeureFin = strtotime($dateFin . ' ' . $heureFin);

        if (empty($idTypeActivite) || !is_numeric($idTypeActivite) || $idTypeActivite == 0) {
            $erreurs['activite'] = "Vous n'avez pas précisé l'activité associé à la réservation.";
        }

        if (empty($salleId) || !is_numeric($idTypeActivite) || $salleId == 0) {
            $erreurs['salle'] = "Vous n'avez pas précisé la salle associée à la réservation.";
        }

        if (empty($idEmploye) || !is_numeric($idEmploye)) {
            $erreurs['employe'] = "Vous n'avez pas précisé l'employé associé à la réservation.";
        }

        if (empty($description)) {
            $erreurs['description'] = 'La description est obligatoire.';
        }

        if (!empty($erreurs)) {
            throw new FieldValidationException($erreurs);
        }

        $req = $pdo->prepare("UPDATE reservation 
                SET dateDebut = :dateDebut, 
                    dateFin = :dateFin, 
                    idActivite = :idActivite, 
                    idSalle = :idSalle, 
                    idEmploye = :idEmploye, 
                    description = :description 
                WHERE identifiant = :id");
        $req->execute(array(
            'dateDebut' => date('Y-m-d H:i:s', $dateHeureDebut),
            'dateFin' => date('Y-m-d H:i:s', $dateHeureFin),
            'idActivite' => $idTypeActivite,
            'idSalle' => $salleId,
            'idEmploye' => $idEmploye,
            'description' => $description,
            'id' => $idReservation
        ));
    }

    public function associerOrganisationReservation($idReservation, $idOrganisme)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("UPDATE reservation SET idOrganisation = :idOrganisation WHERE identifiant = :identifiant");
        $req->execute(array('idOrganisation' => $idOrganisme, 'identifiant' => $idReservation));
    }
}
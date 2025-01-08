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
     * @param $idReservation int, L'identifiant de la réservation à récuperer
     * @return mixed, Retourne la réservation obtenue
     */
    public function getReservation($idReservation) {
        global $pdo;

        $req = $pdo->prepare("SELECT reservation.identifiant as IDENTIFIANT_RESERVATION, dateDebut, dateFin,reservation.idOrganisation AS ORGANISATION, description, activite.type AS ACTIVITE, salle.nom AS NOM_SALLE, individu.nom AS NOM_EMPLOYE, individu.prenom AS PRENOM_EMPLOYE, reservation.idFormateur AS FORMATEUR    
                                    FROM reservation 
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
     * @param $idOrganisme int, L'identifiant de l'organisation à récuperer
     * @return mixed, Retourne l'organisation obtenue
     */
    public function getOrganisation($idOrganisme)
    {
        global $pdo;

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
     * @param $nomFormateur string nom du formateur s'il y en a un
     * @param $prenomnomFormateur string prenom du formateur s'il y en a un
     * @param $telFormateur int telephone du formateur s'il y en a un
     * @param $employe int identifiant de l'employé qui a effectué cette réservation
     * @param $nomOrganisation string nom de l'organisation s'il y en a un
     * @param $description string description de l'activité effectuée lors de la réservation
     */
    public function ajouterReservation($dateDebut, $dateFin, $salle, $activite, $nomFormateur, $prenomFormateur, $telFormateur, $employe, $nomOrganisation, $description)
    {
        $pdo = Database::getPDO();

        // Validation des paramètres
        $erreurs = [];

        // Validation des dates
        if (empty($dateDebut) || empty($dateFin)) {
            $erreurs["dates"] = "Les dates de début et de fin sont requises.";
        } elseif (strtotime($dateDebut) >= strtotime($dateFin)) {
            $erreurs["dates"] = "La date de début doit être antérieure à la date de fin.";
        } elseif (strtotime($dateDebut) < strtotime(date('Y-m-d'))) {
            $erreurs["dates"] = "La date de début ne peut pas être inférieure à aujourd'hui.";
        } else {
            // Validation pour s'assurer que les dates sont sur le même jour
            $jourDebut = date('Y-m-d', strtotime($dateDebut));
            $jourFin = date('Y-m-d', strtotime($dateFin));
            if ($jourDebut !== $jourFin) {
                $erreurs["dates"] = "Les réservations doivent être sur une seule journée.";
            }

            // Validation des heures
            $heureDebut = date('H:i:s', strtotime($dateDebut));
            $heureFin = date('H:i:s', strtotime($dateFin));
            $heureMin = '07:00:00';
            $heureMax = '20:00:00';

            if ($heureDebut < $heureMin || $heureDebut > $heureMax || $heureFin < $heureMin || $heureFin > $heureMax) {
                $erreurs["heures"] = "Les heures doivent être comprises entre 07:00:00 et 20:00:00.";
            }
        }

        // Validation des autres champs obligatoires
        if (empty($salle) || !is_numeric($salle) || $salle == 0) {
            $erreurs["salle"] = "Un identifiant de salle valide est requis.";
        }

        if (empty($activite) || !is_numeric($activite) || $activite == 0) {
            $erreurs["activite"] = "Un identifiant d'activité valide est requis.";
        }

        if (empty($employe) || !is_numeric($employe)) {
            $erreurs["employe"] = "Un identifiant d'employé valide est requis.";
        }

        // Gestion de l'organisation ou du formateur
        $organisation = null;
        $formateur = null;

        if (!empty(trim($nomOrganisation))) {
            // Gestion de l'organisation
            $reqVerifOrganisation = $pdo->prepare(
                "SELECT identifiant FROM organisme WHERE nomOrganisme = :nom"
            );
            $reqVerifOrganisation->execute(['nom' => $nomOrganisation]);
            $organisationExiste = $reqVerifOrganisation->fetchColumn();

            if (!$organisationExiste) {
                // Gestion de l'interlocuteur (individu) pour l'organisation
                $interlocuteur = null;
                if (!empty(trim($nomFormateur)) && !empty(trim($prenomFormateur)) && !empty(trim($telFormateur))) {
                    $reqVerifIndividu = $pdo->prepare(
                        "SELECT identifiant FROM individu WHERE nom = :nom AND prenom = :prenom AND telephone = :telephone"
                    );
                    $reqVerifIndividu->execute([
                        'nom' => trim($nomFormateur),
                        'prenom' => trim($prenomFormateur),
                        'telephone' => trim($telFormateur)
                    ]);
                    $interlocuteurExiste = $reqVerifIndividu->fetchColumn();

                    if (!$interlocuteurExiste) {
                        // Insérer un nouvel individu
                        $reqInsertIndividu = $pdo->prepare(
                            "INSERT INTO individu (nom, prenom, telephone) VALUES (:nom, :prenom, :telephone)"
                        );
                        $reqInsertIndividu->execute([
                            'nom' => trim($nomFormateur),
                            'prenom' => trim($prenomFormateur),
                            'telephone' => trim($telFormateur)
                        ]);
                        $interlocuteur = $pdo->lastInsertId();
                    } else {
                        $interlocuteur = $interlocuteurExiste;
                    }
                }

                // Insérer un nouvel organisme
                $reqInsertOrganisation = $pdo->prepare(
                    "INSERT INTO organisme (nomOrganisme, idInterlocuteur) VALUES (:nom, :interlocuteur)"
                );
                $reqInsertOrganisation->execute([
                    'nom' => $nomOrganisation,
                    'interlocuteur' => $interlocuteur
                ]);
                $organisation = $pdo->lastInsertId();
            } else {
                $organisation = $organisationExiste;
            }
        } elseif (!empty(trim($nomFormateur)) && !empty(trim($prenomFormateur)) && !empty(trim($telFormateur))) {
            // Gestion du formateur
            $reqVerifIndividu = $pdo->prepare(
                "SELECT identifiant FROM individu WHERE nom = :nom AND prenom = :prenom AND telephone = :telephone"
            );
            $reqVerifIndividu->execute([
                'nom' => trim($nomFormateur),
                'prenom' => trim($prenomFormateur),
                'telephone' => trim($telFormateur)
            ]);
            $formateurExiste = $reqVerifIndividu->fetchColumn();

            if (!$formateurExiste) {
                // Insérer un nouvel individu
                $reqInsertIndividu = $pdo->prepare(
                    "INSERT INTO individu (nom, prenom, telephone) VALUES (:nom, :prenom, :telephone)"
                );
                $reqInsertIndividu->execute([
                    'nom' => trim($nomFormateur),
                    'prenom' => trim($prenomFormateur),
                    'telephone' => trim($telFormateur)
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
        $req->bindValue(':organisation', $organisation, $organisation === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $req->bindValue(':description', $description);
        $req->execute();
    }

}
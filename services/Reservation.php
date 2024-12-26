<?php

namespace services;

use PDO;

class Reservation
{
    /**
     * Récupère le nombre total de réservations
     * @return mixed Retourne le nombre total de réservations
     */
    public function getNbReservations($idEmploye = null)
    {
        global $pdo;

        $sql = "SELECT COUNT(*) FROM reservation";
        if (!is_null($idEmploye)) {
            $sql .= " WHERE idEmploye = :idEmploye";
        }

        $req = $pdo->prepare($sql);
        if (!is_null($idEmploye)) {
            $req->bindParam(':idEmploye', $idEmploye, PDO::PARAM_INT);
        }

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
        global $pdo;

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

        // Ajout des conditions de filtre
        if (!empty($filtre)) {
            $sql .= " WHERE ";
            $conditions = [];
            foreach ($filtre as $key => $value) {
                if (!empty($value)) {
                    $conditions[] = "$key = :" . str_replace('.', '_', $key);
                }
            }
            $sql .= implode(' AND ', $conditions);
        }

        $sql .= " ORDER BY reservation.identifiant ASC LIMIT :limit OFFSET :offset";

        $req = $pdo->prepare($sql);
        $req->bindParam(':limit', $limit, PDO::PARAM_INT);
        $req->bindParam(':offset', $offset, PDO::PARAM_INT);
        foreach ($filtre as $key => $value) {
            if (!empty($value)) {
                $req->bindParam(":" . str_replace('.', '_', $key), $value[0], $value[1]);
            }
        }

        $req->execute();
        return $req->fetchAll();
    }
}
<?php

namespace services;

use PDO;

class Salle
{
    /**
     * Permet de récuperer la liste des salles dans la base de données.
     *
     * @return mixed, Retourne la liste des salles obtenue
     */
    public function getSalles($offset = 0, $filtre = [], $limit = null) {
        global $pdo;

        if (is_null($limit)) {
            $limit = Config::get('NB_LIGNES');
        }

        $sql = "SELECT identifiant AS 'ID_SALLE', nom AS 'NOM_SALLE', capacite AS 'CAPACITE' , videoProjecteur AS 'VIDEO_PROJECTEUR', ecranXXL AS 'ECRAN_XXL', idOrdinateur AS 'ID_ORDINATEUR' FROM salle";

        // Ajout des conditions de filtre
        // TODO : function filtre à écrire

        $sql .= " ORDER BY identifiant ASC LIMIT :limit OFFSET :offset";

        $req = $pdo->prepare($sql);
        $req->bindParam(':limit', $limit, PDO::PARAM_INT);
        $req->bindParam(':offset', $offset, PDO::PARAM_INT);

        $req->execute();

        return $req->fetchAll();
    }

    /**
     * Permet de supprimer une salle de la base de données
     * @param $nom string le nom de la salle
     * @param $capacite int la capacité de la salle
     * @param $videoProjecteur bool si la salle a un video projecteur
     * @param $ecranXXL bool si la salle a un écran XXL
     * @param $idOrdinateur int l'identifiant de l'ordinateur
     */
    public function ajouterSalle($nom, $capacite, $videoProjecteur, $ecranXXL, $idOrdinateur)
    {
        global $pdo;

        $req = $pdo->prepare("INSERT INTO salle (nom, capacite, videoProjecteur, ecranXXL, idOrdinateur) VALUES (?, ?, ?, ?, ?)");
        $req->execute([$nom, $capacite, $videoProjecteur, $ecranXXL, $idOrdinateur]);
    }

    /**
     * Permet de récupérer le nombre total de salles
     * @return mixed Retourne le nombre total de salles
     */
    public function getNbSalles() {
        global $pdo;

        $req = $pdo->prepare("SELECT COUNT(*) FROM salle");
        $req->execute();
        return $req->fetchColumn();
    }
}
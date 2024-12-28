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
        $pdo = Database::getPDO();

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
     * @param $nbOrdinateurs int le nombre d'ordinateurs dans la salle
     * @param $logiciels string les logiciels installés sur les ordinateurs
     */
    public function ajouterSalle($nom, $capacite, $videoProjecteur, $ecranXXL, $idOrdinateur)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("INSERT INTO salle (nom, capacite, videoProjecteur, ecranXXL, idOrdinateur) VALUES (:nom, :capacite, :videoProjecteur, :ecranXXL, :idOrdinateur)");
        $req->execute([
            'nom' => $nom,
            'capacite' => $capacite,
            'videoProjecteur' => $videoProjecteur,
            'ecranXXL' => $ecranXXL,
            'idOrdinateur' => $idOrdinateur
        ]);
    }

    /**
     * Permet de récupérer le nombre total de salles
     * @return mixed Retourne le nombre total de salles
     */
    public function getNbSalles() {
        $pdo = Database::getPDO();;

        $req = $pdo->prepare("SELECT COUNT(*) FROM salle");
        $req->execute();
        return $req->fetchColumn();
    }
}
<?php

namespace services;

use PDO;

class Salle
{
    /**
     * Permet de récuperer une salle dans la base de données.
     *
     * @param $id int, L'identifiant de la salle à récuperer
     * @return mixed, Retourne la salle obtenue
     */
    public function getSalle($idSalle) {
        global $pdo;

        $req = $pdo->prepare("SELECT identifiant AS 'ID_SALLE', nom AS 'NOM_SALLE', capacite AS 'CAPACITE' , videoProjecteur AS 'VIDEO_PROJECTEUR', ecranXXL AS 'ECRAN_XXL', idOrdinateur AS 'ID_ORDINATEUR' FROM salle WHERE identifiant = :id");
        $req->execute(['id' => $idSalle]);

        return $req->fetch();
    }
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

        $sql = "SELECT identifiant AS 'ID_SALLE', 
                       nom AS 'NOM_SALLE', 
                       capacite AS 'CAPACITE' , 
                       videoProjecteur AS 'VIDEO_PROJECTEUR', 
                       ecranXXL AS 'ECRAN_XXL', 
                       idOrdinateur AS 'ID_ORDINATEUR' 
                FROM salle";

        // Ajout des filtres
        $sql .= SQLHelper::construireConditionsFiltres($filtre);

        $sql .= " ORDER BY identifiant ASC LIMIT :limit OFFSET :offset";

        $req = $pdo->prepare($sql);
        $req->bindParam(':limit', $limit, PDO::PARAM_INT);
        $req->bindParam(':offset', $offset, PDO::PARAM_INT);

        // Liaison des paramètres avec leurs valeurs et types
        SQLHelper::bindValues($req, $filtre);

        $req->execute();

        return $req->fetchAll();
    }

    /**
     * Permet de supprimer une salle de la base de données
     * @param $nom string le nom de la salle
     * @param $capacite int la capacité de la salle
     * @param $videoProjecteur bool si la salle a un video projecteur
     * @param $ecranXXL bool si la salle a un écran XXL
     * @param $idOrdinateur int l'identifiant de l'ordinateur de la salle
     */
    public function ajouterSalle($nom, $capacite, $videoProjecteur, $ecranXXL, $idOrdinateur)
    {
        $pdo = Database::getPDO();

        // Vérification des données
        if (empty($nom)) {
            $erreurs["nom"] = "Le champ nom est requis.";
        }

        if (empty($capacite) || !is_numeric($capacite) || (int)$capacite <= 0) {
            $erreurs["capacite"] = "La capacité doit être un nombre positif.";
        }

        if (!is_numeric($videoProjecteur)){
            $erreurs["videoProjecteur"] = "Le champ videoProjecteur doit être un booléen.";
        }

        if (!is_numeric($ecranXXL)){
            $erreurs["ecranXXL"] = "Le champ ecranXXL doit être un booléen.";
        }

        if (!is_numeric($idOrdinateur)){
            $erreurs["idOrdinateur"] = "Le champ idOrdinateur doit être un nombre.";
        }

        if (!empty($erreurs)) {
            throw new \Exception($erreurs);
        }

        // Insertion de la salle
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
    public function getNbSalles($filtre = []) {
        $pdo = Database::getPDO();

        $sql = "SELECT COUNT(*) FROM salle";
        $sql .= SQLHelper::construireConditionsFiltres($filtre);
        $req = $pdo->prepare($sql);
        SQLHelper::bindValues($req, $filtre);

        $req->execute();
        return $req->fetchColumn();
    }

    /**
     * Permet de supprimer une salle de la base de données
     * @param $idSalle int l'identifiant de la salle à supprimer
     */
    public function supprimerSalle($idSalle, $nbReservations)
    {
        $pdo = Database::getPDO();

        if ($nbReservations > 0) {
            throw new \Exception("Impossible de supprimer une salle avec des réservations.");
        }

        $req = $pdo->prepare("DELETE FROM salle WHERE identifiant = ?");
        $req->execute([$idSalle]);
    }

}
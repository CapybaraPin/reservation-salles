<?php

namespace services;

use MongoDB\Driver\Exception\ExecutionTimeoutException;
use PDO;
use services\exceptions\FieldValidationException;

class Salle
{
    /**
     * Permet de récuperer une salle dans la base de données.
     *
     * @param $id int, L'identifiant de la salle à récuperer
     * @return mixed, Retourne la salle obtenue
     */
    public function getSalle($idSalle) {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("SELECT identifiant AS 'ID_SALLE', 
                                           nom AS 'NOM_SALLE', 
                                           capacite AS 'CAPACITE' , 
                                           videoProjecteur AS 'VIDEO_PROJECTEUR', 
                                           ecranXXL AS 'ECRAN_XXL', 
                                           idOrdinateur AS 'ID_ORDINATEUR' 
                                    FROM salle 
                                    WHERE identifiant = :id");
        $req->execute(['id' => $idSalle]);

        if($req->rowCount() > 0) {
            return $req->fetch();
        } else {
            throw new \Exception("Salle non trouver");
        }
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

        if (!is_numeric((int)$idOrdinateur)) {
            $erreurs["idOrdinateur"] = "Le format du nombre d'ordinateurs n'est pas valide.";
        }

        if (!empty($erreurs)) {
            throw new FieldValidationException($erreurs);
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
    public function supprimerSalle($idSalle)
    {
        $pdo = Database::getPDO();

        $this->getSalle($idSalle);

        $req = $pdo->prepare("DELETE FROM salle WHERE identifiant = ?");

        $req->execute([$idSalle]);

        return $req;


    }

    /**
     * Met à jour une salle existante dans la base de données
     * @param int $idSalle l'identifiant de la salle à modifier
     * @param string $nom le nom de la salle
     * @param int $capacite la capacité de la salle
     * @param bool $videoProjecteur si la salle a un video projecteur
     * @param bool $ecranXXL si la salle a un écran XXL
     * @throws \Exception si les données ne sont pas valides
     */
    public function modifierSalle($idSalle, $nom, $capacite, $videoProjecteur, $ecranXXL)
    {
        $pdo = Database::getPDO();

        // Validation des données
        $erreurs = [];
        if (empty($nom)) {
            $erreurs['nom'] = "Le champ nom est requis.";
        }
        if ($capacite <= 0) {
            $erreurs['capacite'] = "La capacité doit être un nombre positif.";
        }

        if (!empty($erreurs)) {
            throw new FieldValidationException($erreurs);
        }

        // Mise à jour de la salle
        $req = $pdo->prepare("UPDATE salle 
                              SET nom = :nom, 
                                  capacite = :capacite, 
                                  videoProjecteur = :videoProjecteur, 
                                  ecranXXL = :ecranXXL
                              WHERE identifiant = :idSalle");
        $req->execute([
            'nom' => $nom,
            'capacite' => $capacite,
            'videoProjecteur' => $videoProjecteur,
            'ecranXXL' => $ecranXXL,
            'idSalle' => $idSalle
        ]);
    }
}
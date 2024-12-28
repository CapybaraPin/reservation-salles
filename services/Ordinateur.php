<?php

namespace services;

class Ordinateur
{
    /**
     * Permet de récupérer la liste des logiciels pour les ordinateurs d'une salle dans la base de donnée
     * @param $idOrdinateur int l'identifiant de l'ordinateur
     * @return array, Retourne la liste des logiciels associés à un groupe d'ordinateur.
     */
    public function getLogiciels($idOrdinateur = null)
    {
        $pdo = Database::getPDO();

        if (is_null($idOrdinateur)) {
            $req = $pdo->prepare("SELECT identifiant, nom FROM logiciel");
        } else {
            $req = $pdo->prepare("SELECT logiciel.identifiant, nom FROM ordinateurLogiciel JOIN logiciel ON ordinateurLogiciel.idLogiciel = logiciel.identifiant WHERE ordinateurLogiciel.idOrdinateur = ?");
        }

        $req->execute();
        return $req->fetchAll();
    }

    /**
     * Permet de récupérer la liste des types d'ordinateur dans la base de donnée
     * @return array, Retourne la liste des types d'ordinateur
     */
    public function getTypesOrdinateur()
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("SELECT identifiant, type FROM typeOrdinateur");
        $req->execute();
        return $req->fetchAll();
    }

    /**
     * Permet de récupérer la liste des oridnateur pour une salle dans la base de donnée
     * @param $idOrdinateur
     * @return array, Retourne la liste des ordinateurs pour une salle
     */
    public function getOrdinateur($idOrdinateur)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("SELECT groupeOrdinateur.identifiant, nbOrdinateur, imprimante, idType, type AS DesignationType FROM groupeOrdinateur JOIN typeOrdinateur ON idType = typeOrdinateur.identifiant WHERE groupeOrdinateur.identifiant = ?");
        $req->execute(array($idOrdinateur));
        return $req->fetchAll();
    }

    /**
     * Permet d'ajouter un groupe d'ordinateur dans la base de donnée
     * @param $nbOrdinateurs int le nombre d'ordinateurs
     * @param $imprimante bool si le groupe possède une imprimante
     * @param $typeOrdinateur int le type d'ordinateur
     * @return string, Retourne l'identifiant du groupe d'ordinateur ajouté
     */
    public function ajouterGroupeOrdinateur($nbOrdinateurs, $imprimante, $typeOrdinateur)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("INSERT INTO groupeOrdinateur (nbOrdinateur, imprimante, idType) VALUES (:nbOrdinateurs, :imprimante, :typeOrdinateur)");
        $req->execute([
            'nbOrdinateurs' => $nbOrdinateurs,
            'imprimante' => $imprimante,
            'typeOrdinateur' => $typeOrdinateur
        ]);

        return $pdo->lastInsertId();
    }

    /**
     * Permet d'ajouter un logiciel à un ordinateur
     * @param $idOrdinateur int l'identifiant de l'ordinateur
     * @param $idLogiciel int l'identifiant du logiciel
     */
    public function ajouterLogiciel($idOrdinateur, $idLogiciel)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("INSERT INTO ordinateurLogiciel (idOrdinateur, idLogiciel) VALUES (:idOrdinateur, :idLogiciel)");
        $req->execute([
            'idOrdinateur' => $idOrdinateur,
            'idLogiciel' => $idLogiciel
        ]);
    }

    /**
     * Permet de récupérer toutes les informations du groupe d'ordinateur associé à une salle
     * @param $idSalle int l'identifiant de la salle
     * @return mixed, Retourne les informations du groupe d'ordinateur associé à une salle
     */
    public function getOrdinateursSalle($idSalle) {
        global $pdo;

        $req = $pdo->prepare("SELECT groupeOrdinateur.identifiant AS 'ID_GROUPE_ORDINATEUR', 
                                            nbOrdinateur AS 'NB_ORDINATEUR', 
                                            imprimante AS 'IMPRIMANTE', 
                                            idType AS 'ID_TYPE', 
                                            type AS 'DESIGNATION_TYPE'
                                            FROM groupeOrdinateur 
                                            JOIN typeOrdinateur 
                                            ON idType = typeOrdinateur.identifiant 
                                            WHERE groupeOrdinateur.identifiant = (SELECT idOrdinateur FROM salle WHERE identifiant = ?)");
        $req->execute([$idSalle]);

        return $req->fetch();
    }

    /**
     * Permet de récupérer la liste des logiciels associés à un ordinateur
     * @param $idOrdinateur int l'identifiant de l'ordinateur
     */
    public function getLogicielsOrdinateur($idOrdinateur)
    {
        global $pdo;

        $req = $pdo->prepare("SELECT logiciel.identifiant AS 'ID_LOGICIEL', 
                                            nom AS 'NOM_LOGICIEL' 
                                    FROM ordinateurLogiciel 
                                    JOIN logiciel 
                                    ON ordinateurLogiciel.idLogiciel = logiciel.identifiant 
                                    WHERE ordinateurLogiciel.idOrdinateur = ?");
        $req->execute([$idOrdinateur]);

        return $req->fetchAll();
    }
}
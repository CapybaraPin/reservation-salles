<?php

namespace services;

use PDO;

class Employe
{
    /**
     * Permet d'ajouter un employé dans la base de données
     * @param $nomEmploye string le nom de l'employé
     * @param $prenomEmploye string le prénom de l'employé
     * @param $telephoneEmploye string le numéro de téléphone de l'employé
     * @param $identifiantEmploye string l'identifiant de l'employé
     * @param $motDePasseEmploye string le mot de passe de l'employé
     */
    public function ajouterEmploye($nomEmploye, $prenomEmploye, $telephoneEmploye, $identifiantEmploye, $motDePasseEmploye)
    {
        global $pdo;

        // Insérer dans la table individu
        $reqIndividu = $pdo->prepare("INSERT INTO individu (nom, prenom, telephone) VALUES (?, ?, ?)");
        $reqIndividu->execute([$nomEmploye, $prenomEmploye, $telephoneEmploye]);

        // Récupérer l'identifiant de l'individu récemment inséré
        $idIndividu = $pdo->lastInsertId();

        // Insérer dans la table utilisateur
        $reqUtilisateur = $pdo->prepare("INSERT INTO utilisateur (identifiant, motDePasse, role, individu) VALUES (?, ?, ?, ?)");
        $reqUtilisateur->execute([$identifiantEmploye, $motDePasseEmploye, 0, $idIndividu]);
    }

    /**
     * Permet de supprimer un employé de la base de données
     * @param $idEmploye int l'identifiant de l'employé
     * @return bool true si les suppressions sont bien effectuées
     */
    public function suppressionEmploye($idEmploye){
        global $pdo;

        try {

            // Suppression de l'utilisateur`
            $req = $pdo->prepare("DELETE FROM utilisateur WHERE individu = ?");
            $utilisateurs = $req->execute([$idEmploye]);

            // Suppression de l'individu
            $req1 = $pdo->prepare("DELETE FROM individu WHERE identifiant = ?");
            $individus = $req1->execute([$idEmploye]);

            return $individus && $utilisateurs;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getNbEmployes($filtre = [])
    {
        global $pdo;

        $sql = "SELECT COUNT(*) FROM individu";
        $sql .= SQLHelper::construireConditionsFiltres($filtre);

        $req = $pdo->prepare($sql);
        SQLHelper::bindValues($req, $filtre);

        $req->execute();
        return $req->fetchColumn();
    }

    public function getEmployes($offset = 0, $filtre = [], $limit = null)
    {
        global $pdo;

        if (is_null($limit)) {
            $limit = Config::get('NB_LIGNES');
        }

        try {
            $sql = "SELECT 
                    identifiant AS 'IDENTIFIANT_EMPLOYE', 
                    nom AS 'NOM_EMPLOYE', 
                    prenom AS 'PRENOM_EMPLOYE', 
                    telephone AS 'TELEPHONE_EMPLOYE' 
                FROM individu ";

            $sql .= SQLHelper::construireConditionsFiltres($filtre);
            $sql .= " ORDER BY identifiant ASC LIMIT :limit OFFSET :offset";

            $req = $pdo->prepare($sql);
            $req->bindParam(':limit', $limit, PDO::PARAM_INT);
            $req->bindParam(':offset', $offset, PDO::PARAM_INT);
            SQLHelper::bindValues($req, $filtre);

            $req->execute();
            $result = $req->fetchAll();

            return $result ?: []; // Retourne un tableau vide si aucun résultat
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    /**
     * Permet de récupérer un identifiant de réservation pour un employé si il y en a un
     * @param $idEmploye int l'identifiant de l'employé
     * @return bool renvoie true si il y a un resultat sinon ne renvoie rien
     */
    public function verifReservationEmploye($idEmploye)
    {
        global $pdo;

        $req = $pdo->prepare("SELECT identifiant FROM reservation WHERE idEmploye = ?");
        $req->execute(array($idEmploye));

        return $req->rowCount() > 0;
    }
}
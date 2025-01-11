<?php

namespace services;

use PDO;
use services\exceptions\FieldValidationException;

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
        $pdo = Database::getPDO();

        $pdo->beginTransaction();

        try {
            // Insérer dans la table individu
            $reqIndividu = $pdo->prepare("INSERT INTO individu (nom, prenom, telephone) VALUES (?, ?, ?)");
            $reqIndividu->execute([$nomEmploye, $prenomEmploye, $telephoneEmploye]);

            // Récupérer l'identifiant de l'individu récemment inséré
            $idIndividu = $pdo->lastInsertId();

            // Insérer dans la table utilisateur
            $reqUtilisateur = $pdo->prepare("INSERT INTO utilisateur (identifiant, motDePasse, role, individu) VALUES (?, ?, ?, ?)");
            $reqUtilisateur->execute([$identifiantEmploye, $motDePasseEmploye, 0, $idIndividu]);

            $pdo->commit();
        } catch (\PDOException $e) {
            $pdo->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Permet de supprimer un employé de la base de données
     * @param $idEmploye int l'identifiant de l'employé
     * @return bool true si les suppressions sont bien effectuées
     */
    public function suppressionEmploye($idEmploye)
    {
        $pdo = Database::getPDO();
        $pdo->beginTransaction();

        try {

            // Suppression de l'utilisateur`
            $req = $pdo->prepare("DELETE FROM utilisateur WHERE individu = ?");
            $utilisateurs = $req->execute([$idEmploye]);

            // Suppression de l'individu
            $req1 = $pdo->prepare("DELETE FROM individu WHERE identifiant = ?");
            $individus = $req1->execute([$idEmploye]);

            $pdo->commit();

            return $individus && $utilisateurs;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            $pdo->rollBack();
            return false;
        }
    }

    public function getNbEmployes($filtre = [])
    {
        $pdo = Database::getPDO();

        $sql = "SELECT COUNT(*) FROM individu";
        $sql .= SQLHelper::construireConditionsFiltres($filtre);

        $req = $pdo->prepare($sql);
        SQLHelper::bindValues($req, $filtre);

        $req->execute();
        return $req->fetchColumn();
    }

    public function getEmployes($offset = 0, $filtre = [], $limit = null)
    {
        $pdo = Database::getPDO();

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
        $pdo = Database::getPDO();

        $req = $pdo->prepare("SELECT identifiant FROM reservation WHERE idEmploye = ?");
        $req->execute(array($idEmploye));

        return $req->rowCount() > 0;
    }
  
     /* 
     * Récupère les informations d'un employé en fonction de son ID
     * @param $idEmploye
     * @return mixed
     */
    public function getEmploye($idEmploye)
    {
        $pdo = Database::getPDO();
        $req = $pdo->prepare("SELECT identifiant AS 'ID_EMPLOYE', nom AS 'NOM_EMPLOYE', prenom AS 'PRENOM_EMPLOYE', telephone AS 'TELEPHONE_EMPLOYE' FROM individu WHERE identifiant = ?");
        $req->execute([$idEmploye]);

        if($req->rowCount() < 1) {
            throw new \Exception("L'employé avec l'ID ". $idEmploye . " n'existe pas.");
        }

        return $req->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Modification des informations d'un employé en fonction de toutes les informations dans la base de données.
     * @param $idEmploye
     * @param $nom
     * @param $prenom
     * @param $telephone
     * @return void
     */
    public function modifierEmploye($idEmploye, $nom, $prenom, $telephone)
    {
        $pdo = Database::getPDO();

        // Validation des données
        $erreurs = [];
        if (empty($nom)) {
            $erreurs['nom'] = "Le champ nom est requis.";
        }
        if (empty($prenom)) {
            $erreurs['prenom'] = "Le champ prénom est requis.";
        }
        if (!preg_match('/^(?:\+33|0)[1-9](?:[\d]{2}){4}$/', $telephone)) {
            $erreurs['telephone'] = "Le numéro de téléphone est invalide.";
        }

        if (!empty($erreurs)) {
            throw new FieldValidationException($erreurs);
        }

        // Mise à jour de l'employé
        $req = $pdo->prepare("
        UPDATE individu 
        SET nom = :nom, 
            prenom = :prenom, 
            telephone = :telephone 
        WHERE identifiant = :idEmploye
    ");
        $req->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'telephone' => $telephone,
            'idEmploye' => $idEmploye
        ]);
    }

    public function modifierMotDePasse($idEmploye, $pass) {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("UPDATE utilisateur SET motDePasse = ? WHERE individu = ?");
        $req->execute(array($pass, $idEmploye));

        return $req;
    }
  
    /**
     *Permet de récupérer les information d'un individu
     * @param $idIndividu int identifiant de l'individu rechercher
     * @return mixed, Retourne l'individu obtenue
     */
    public function getindividu($idIndividu){
        $pdo = Database::getPDO();
        $req = $pdo->prepare("SELECT nom, prenom, telephone FROM individu WHERE identifiant = :id ");
        $req->execute(['id' => $idIndividu]);
        return $req->fetch();
    }

}
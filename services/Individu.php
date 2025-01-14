<?php

namespace services;

use PDO;
use services\exceptions\FieldValidationException;

/**
 * Classe pour les individus
 */
class Individu
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
            $idIndividu = $this->ajouterIndividu($nomEmploye, $prenomEmploye, $telephoneEmploye);

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

        $sql = "SELECT COUNT(*) FROM individu
                JOIN utilisateur
                ON individu.identifiant = utilisateur.individu";
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
                    individu.identifiant AS 'IDENTIFIANT_EMPLOYE', 
                    nom AS 'NOM_EMPLOYE', 
                    prenom AS 'PRENOM_EMPLOYE', 
                    telephone AS 'TELEPHONE_EMPLOYE' 
                FROM individu
                JOIN utilisateur
                ON individu.identifiant = utilisateur.individu";


            $sql .= SQLHelper::construireConditionsFiltres($filtre);
            $sql .= " ORDER BY individu.identifiant ASC LIMIT :limit OFFSET :offset";

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
        $req = $pdo->prepare("SELECT individu.identifiant AS 'ID_EMPLOYE', nom AS 'NOM_EMPLOYE', prenom AS 'PRENOM_EMPLOYE', telephone AS 'TELEPHONE_EMPLOYE'
            FROM individu 
            JOIN utilisateur
            ON individu.identifiant = utilisateur.individu
            WHERE individu.identifiant = ?");
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
        if($telephone != null) {
            if (!preg_match('/^\d{4}$/', $telephone)) {
                $erreurs['telephone'] = "Le numéro de téléphone est invalide.";
            }
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

    public function getID($idEmploye) {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("SELECT identifiant FROM utilisateur WHERE individu = ?");
        $req->execute(array($idEmploye));

        return $req->fetch();
    }

    public function modifierIdentifiant($idEmploye, $newid) {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("UPDATE utilisateur SET identifiant = ? WHERE individu = ?");
        $req->execute(array($newid, $idEmploye));

        return $req;
    }

    public function modifierMotDePasse($idEmploye, $pass) {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("UPDATE utilisateur SET motDePasse = ? WHERE individu = ?");
        $req->execute(array($pass, $idEmploye));

        return $req;
    }
  
    /**
     * Permet de récupérer les informations d'un individu
     * @param $idIndividu int identifiant de l'individu recherché
     * @return mixed, Retourne l'individu obtenu
     */
    public function getindividu($idIndividu){
        $pdo = Database::getPDO();
        $req = $pdo->prepare("SELECT nom, prenom, telephone FROM individu WHERE identifiant = :id ");
        $req->execute(['id' => $idIndividu]);
        return $req->fetch();
    }

    /**
     * Permet de récuperer l'identifiant d'un individu
     * en fonction de son nom, prenom et telephone
     */
    public function getIdIndividu($nom, $prenom, $telephone)
    {
        $pdo = Database::getPDO();
        $req = $pdo->prepare("SELECT identifiant FROM individu WHERE nom = :nom AND prenom = :prenom AND telephone = :telephone");
        $req->execute(['nom' => $nom, 'prenom' => $prenom, 'telephone' => $telephone]);
        return $req->fetch();
    }

    /**
     *
     * @param string $nomEmploye
     * @param string $prenomEmploye
     * @param string $telephoneEmploye
     * @return false|string
     */
    public function ajouterIndividu($nomEmploye, $prenomEmploye, $telephoneEmploye)
    {
        $pdo = Database::getPDO();

        $reqIndividu = $pdo->prepare("INSERT INTO individu (nom, prenom, telephone) VALUES (?, ?, ?)");
        $reqIndividu->execute([$nomEmploye, $prenomEmploye, $telephoneEmploye]);

        // Récupérer l'identifiant de l'individu récemment inséré
        $idIndividu = $pdo->lastInsertId();
        return $idIndividu;
    }

    /**
     * Récupère les individus
     */
    public function getIndividus()
    {
        $pdo = Database::getPDO();
        $req = $pdo->prepare("SELECT 
        identifiant AS ID_INDIVIDU,
        nom AS NOM_INDIVIDU,
        prenom AS PRENOM_INDIVIDU
        FROM individu");
        $req->execute();
        return $req->fetchAll();
    }

    /**
     * Permet de savoir si un individu existe via son identifiant
     */
    public function individuExiste($idIndividu)
    {
        $pdo = Database::getPDO();
        $req = $pdo->prepare("SELECT identifiant FROM individu WHERE identifiant = ?");
        $req->execute([$idIndividu]);
        return $req->rowCount() > 0;
    }


}
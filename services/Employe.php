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
        // Insérer dans la table individu
        $reqIndividu = $this->getPDO()->prepare("INSERT INTO individu (nom, prenom, telephone) VALUES (?, ?, ?)");
        $reqIndividu->execute([$nomEmploye, $prenomEmploye, $telephoneEmploye]);

        // Récupérer l'identifiant de l'individu récemment inséré
        $idIndividu = $this->getPDO()->lastInsertId();

        // Insérer dans la table utilisateur
        $reqUtilisateur = $this->getPDO()->prepare("INSERT INTO utilisateur (identifiant, motDePasse, role, individu) VALUES (?, ?, ?, ?)");
        $reqUtilisateur->execute([$identifiantEmploye, $motDePasseEmploye, 0, $idIndividu]);
    }

    /**
     * Permet de supprimer un employé de la base de données
     * @param $idEmploye int l'identifiant de l'employé
     * @return bool true si les suppressions sont bien effectuées
     */
    public function suppressionEmploye($idEmploye){
        try {

            // Suppression de l'utilisateur`
            $req = $this->getPDO()->prepare("DELETE FROM utilisateur WHERE individu = ?");
            $utilisateurs = $req->execute([$idEmploye]);

            // Suppression de l'individu
            $req1 = $this->getPDO()->prepare("DELETE FROM individu WHERE identifiant = ?");
            $individus = $req1->execute([$idEmploye]);

            return $individus && $utilisateurs;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Récupère la liste des employés.
     * @return mixed Retourne la liste des employés
     */
    public function getEmployes($offset = 0, $filtre = [], $limit = null)
    {
        if (is_null($limit)) {
            $limit = Config::get('NB_LIGNES');
        }

        $sql = "SELECT 
                    i.identifiant AS 'IDENTIFIANT_EMPLOYE', 
                    i.nom AS 'NOM_EMPLOYE', 
                    i.prenom AS 'PRENOM_EMPLOYE', 
                    i.telephone AS 'TELEPHONE_EMPLOYE' 
                FROM individu i";

        // Ajout des conditions de filtre
        // TODO : function filtre à écrire

        $sql .= " ORDER BY i.identifiant ASC LIMIT :limit OFFSET :offset";

        $req = $this->getPDO()->prepare($sql);

        $req->bindParam(':limit', $limit, PDO::PARAM_INT);
        $req->bindParam(':offset', $offset, PDO::PARAM_INT);

        $req->execute();
        return $req->fetchAll();
    }

    /**
     * @return mixed Retourne le nombre total d'employés
     */
    public function getNbEmployes() {
        $req = $this->getPDO()->prepare("SELECT COUNT(*) FROM individu");
        $req->execute();
        return $req->fetchColumn();
    }

    /**
     * Permet de récupérer un identifiant de réservation pour un employé si il y en a un
     * @param $idEmploye int l'identifiant de l'employé
     * @return bool renvoie true si il y a un resultat sinon ne renvoie rien
     */
    public function verifReservationEmploye($idEmploye)
    {
        $req = $this->getPDO()->prepare("SELECT identifiant FROM reservation WHERE idEmploye = ?");
        $req->execute(array($idEmploye));

        return $req->rowCount() > 0;
    }
}
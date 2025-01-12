<?php

namespace services;

class Organisme
{
    /**
     * Récupère les informations d'une organisation
     * @param int $idOrganisation Identifiant de l'organisation
     * @return array informations de l'organisation
     */
    public function getInterlocuteur($idOrganisation)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("SELECT
            individu.nom AS 'NOM_INTERLOCUTEUR',
            individu.prenom AS 'PRENOM_INTERLOCUTEUR',
            individu.telephone AS 'TELEPHONE_INTERLOCUTEUR'
            FROM individu
            JOIN organisme
            ON organisme.idInterlocuteur = individu.identifiant
            WHERE organisme.identifiant = ?");

        $req->execute([$idOrganisation]);

        return $req->fetch();
    }

    /**
     * Récupère l'id d'un interlocuteur
     */
    public function getIdInterlocuteur($nomInterlocuteur, $prenomInterlocuteur, $telInterlocuteur)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("
                SELECT organisme.idInterlocuteur FROM organisme 
                JOIN individu ON organisme.idInterlocuteur = individu.identifiant
                WHERE individu.nom = ? AND individu.prenom = ? AND individu.telephone = ?
                ");
        $req->execute([$nomInterlocuteur, $prenomInterlocuteur, $telInterlocuteur]);

        return $req->fetch();
    }

    /**
     * Récupère l'id d'une organisation
     * @param String $nomOrganisation Nom de l'organisation
     * @return int Identifiant de l'organisation
     */
    public function getIdOrganisation($nomOrganisation)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("SELECT identifiant FROM organisme WHERE nomOrganisme = ?");
        $req->execute([$nomOrganisation]);

        return $req->fetch();
    }

    /**
     * Insère un interlocuteur
     * @param String $nomInterlocuteur Nom de l'interlocuteur
     * @param String $prenomInterlocuteur Prénom de l'interlocuteur
     * @param String $telInterlocuteur Téléphone de l'interlocuteur
     * @return int Identifiant de l'interlocuteur
     */
    public function ajouterInterlocuteur($nomInterlocuteur, $prenomInterlocuteur, $telInterlocuteur)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("INSERT INTO individu (nom, prenom, telephone) VALUES (?, ?, ?)");
        $req->execute([$nomInterlocuteur, $prenomInterlocuteur, $telInterlocuteur]);

        return $pdo->lastInsertId();
    }

    /**
     * Insère une organisation
     * @param String $nomOrganisation Nom de l'organisation
     * @param int $idInterlocuteur Identifiant de l'interlocuteur
     * @return int Identifiant de l'organisation
     */
    public function ajouterOrganisation($nomOrganisation, $idInterlocuteur)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("INSERT INTO organisme (nomOrganisme, idInterlocuteur) VALUES (?, ?)");
        $req->execute([$nomOrganisation, $idInterlocuteur]);

        return $pdo->lastInsertId();
    }

}
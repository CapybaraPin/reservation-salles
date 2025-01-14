<?php

namespace services;

use services\exceptions\FieldValidationException;

/**
 * Classe pour les organismes
 */
class Organisme
{
    public function getOrganismes()
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("SELECT 
            organisme.identifiant AS 'IDENTIFIANT_ORGANISME',
            nomOrganisme AS 'NOM_ORGANISME',
            idInterlocuteur AS 'ID_INTERLOCUTEUR',
            individu.nom AS 'NOM_INTERLOCUTEUR',
            individu.prenom AS 'PRENOM_INTERLOCUTEUR'
            FROM organisme
            JOIN individu
            ON organisme.idInterlocuteur = individu.identifiant");
        $req->execute();

        return $req->fetchAll();
    }
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
    public function getIdOrganisme($nomOrganisation)
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

        if (empty($nomInterlocuteur)) {
            $erreurs["Nom"] = "Merci d'indiquer un nom valide.";
        }

        if (empty($prenomInterlocuteur)) {
            $erreurs["Prenom"] = "Merci d'indiquer un prénom valide.";
        }

        if (empty($telInterlocuteur) || !is_numeric($telInterlocuteur)) {
            $erreurs["Telephone"] = "Merci d'indiquer un numéro de téléphone valide.";
        }

        if (!empty($erreurs)) {
            throw new FieldValidationException($erreurs);
        }

        $req = $pdo->prepare("INSERT INTO individu (nom, prenom, telephone) VALUES (?, ?, ?)");
        $req->execute([$nomInterlocuteur, $prenomInterlocuteur, $telInterlocuteur]);

        return $pdo->lastInsertId();
    }

    /**
     * Insère un organisme
     * @param String $nomOrganisation Nom de l'organisme
     * @param int $idInterlocuteur Identifiant de l'interlocuteur
     * @return int Identifiant de l'organisme
     */
    public function ajouterOrganisme($nomOrganisation, $idInterlocuteur)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("INSERT INTO organisme (nomOrganisme, idInterlocuteur) VALUES (?, ?)");
        $req->execute([$nomOrganisation, $idInterlocuteur]);

        return $pdo->lastInsertId();
    }

    /**
     * Retourne un boolean pour savoir si un organisme existe via son identifiant
     * @param int $idOrganisme Identifiant de l'organisme
     * @return bool true si l'organisme existe, false sinon
     */
    public function organismeExiste($idOrganisme)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("SELECT COUNT(*) FROM organisme WHERE identifiant = ?");
        $req->execute([$idOrganisme]);

        return $req->fetchColumn() > 0;
    }

}
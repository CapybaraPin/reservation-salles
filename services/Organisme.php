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
}
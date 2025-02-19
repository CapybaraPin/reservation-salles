<?php

namespace services;

/**
 * Classe pour les activités
 */
class Activite
{
    /**
     * Permet de récupérer la liste des activités dans la base de données.
     *
     * @return array, Retourne la liste des activités obtenue
     */
    public function getActivites()
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("SELECT 
                                            identifiant AS 'IDENTIFIANT_ACTIVITE',
                                            type AS 'TYPE_ACTIVITE'
                                            FROM activite");
        $req->execute();
        return $req->fetchAll();
    }

    /**
     * Permet de récupérer le nombre total d'activités
     * @return mixed Retourne le nombre total d'activités
     */
    public function getNbActivites()
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("SELECT COUNT(*) FROM activite");
        $req->execute();
        return $req->fetchColumn();
    }
}
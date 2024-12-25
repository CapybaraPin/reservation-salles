<?php

namespace services;

class Activite extends Database
{
    /**
     * Permet de récupérer la liste des activités dans la base de données.
     *
     * @return array, Retourne la liste des activités obtenue
     */
    public function getActivites()
    {
        $req = $this->getPDO()->prepare("SELECT 
                                            identifiant AS 'IDENTIFIANT_ACTIVITE',
                                            type AS TYPE_ACTIVITE
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
        $req = $this->getPDO()->prepare("SELECT COUNT(*) FROM activite");
        $req->execute();
        return $req->fetchColumn();
    }
}
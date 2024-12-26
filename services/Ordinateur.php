<?php

namespace services;

class Ordinateur
{
    /**
     * Permet de récupérer la liste des logiciels pour les ordinateurs d'une salle dans la base de donnée
     * @param $idOrdinateur int l'identifiant de l'ordinateur
     * @return array, Retourne la liste des logiciels associés à un groupe d'ordinateur.
     */
    public function getLogiciels($idOrdinateur = null) {
        global $pdo;

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
    public function getTypesOrdinateur(){
        global $pdo;

        $req = $pdo->prepare("SELECT identifiant, type FROM typeOrdinateur");
        $req->execute();
        return $req->fetchAll();
    }

    /**
     * Permet de récupérer la liste des oridnateur pour une salle dans la base de donnée
     * @param $idOrdinateur
     * @return array, Retourne la liste des ordinateurs pour une salle
     */
    public function getOrdinateur($idOrdinateur) {
        global $pdo;

        $req = $pdo->prepare("SELECT groupeOrdinateur.identifiant, nbOrdinateur, imprimante, idType, type AS DesignationType FROM groupeOrdinateur JOIN typeOrdinateur ON idType = typeOrdinateur.identifiant WHERE groupeOrdinateur.identifiant = ?");
        $req->execute(array($idOrdinateur));
        return $req->fetchAll();
    }
}
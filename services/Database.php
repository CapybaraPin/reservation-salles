<?php

namespace services;

use PDO;
use PDOException;

/**
 * Classe Database
 *
 * Cette classe permet d'établir une connexion avec une base de données MySQL en utilisant PDO.
 * Elle fournit une méthode pour obtenir une instance PDO configurée et prête à l'emploi.
 *
 * @package services
 */
class Database
{
    private $pdo;

    /**
     * Établit une connexion à la base de données et retourne l'instance PDO.
     *
     * Cette méthode utilise les informations de configuration (hôte, nom de la base de données,
     * charset, utilisateur et mot de passe) récupérées à partir du fichier `.env` via la classe `Config`.
     * Si la connexion échoue, une exception PDOException est levée.
     *
     * @return PDO|null Retourne une instance PDO représentant la connexion à la base de données,
     *                  ou `null` en cas d'échec de la connexion.
     * @throws PDOException Si la connexion échoue, une exception est lancée.
     */
    public function getPDO()
    {
        try {
            // Récupère les informations de configuration depuis le fichier .env
            $host = Config::get('DB_HOST');
            $dbname = Config::get('DB_NAME');
            $charset = Config::get('DB_CHARSET');
            $user = Config::get('DB_USER');
            $password = Config::get('DB_PASSWORD');

            // Options de configuration de PDO
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password, $options);
            $this->pdo->exec("SET CHARACTER SET utf8");

            return $this->pdo;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    /**
     * Récupère la liste des employés.
     * @return mixed Retourne la liste des employés
     */
    public function getEmployes()
    {
        $req = $this->pdo->prepare("SELECT 
                                           DISTINCT(i.identifiant) AS 'IDENTIFIANT_EMPLOYE', 
                                           i.nom AS 'NOM_EMPLOYE', 
                                           i.prenom AS 'PRENOM_EMPLOYE', 
                                           i.telephone AS 'TELEPHONE_EMPLOYE' 
                                    FROM individu i
                                    JOIN utilisateur u
                                    ON u.role = 0;");

        $req->execute();
        return $req->fetchAll();
    }

    public function getActivites()
    {
        $req = $this->pdo->prepare("SELECT 
                                            identifiant AS 'IDENTIFIANT_ACTIVITE',
                                            type AS TYPE_ACTIVITE
                                            FROM activite");
        $req->execute();
        return $req->fetchAll();
    }

    /**
     * Permet de récuperer la listes des salles dans la base de données.
     *
     * @return PDOStatement, Retourne la liste des salles obtenue
     */
    public function getSalles() {

        $req = $this->pdo->query("SELECT identifiant, nom, capacite, videoProjecteur, ecranXXL, idOrdinateur FROM salle");
        return $req->fetchAll();
    }

    /**
     * Permet de récupérer la liste des oridnateur pour une salle dans la base de donnée
     * @param $idOrdinateur
     * @return PDOStatement, Retourne la liste des ordinateur pour une salle
     */
    public function getOrdinateur($idOrdinateur) {

        $req = $this->pdo->prepare("SELECT groupeOrdinateur.identifiant, nbOrdinateur, imprimante, idType, type AS DesignationType FROM groupeOrdinateur JOIN typeOrdinateur ON idType = typeOrdinateur.identifiant WHERE groupeOrdinateur.identifiant = ?");
        $req->execute(array($idOrdinateur));
        return $req->fetchAll();
    }

    /**
     * Permet de récupérer la liste des logiciel pour les ordinateur d'une salle dans la base de donnée
     * @param $idOrdinateur
     * @return PDOStatement, Retourne la liste des logiciels associés a un groupe d'ordinateur.
     */
    public function getLogiciel($idOrdinateur) {

        $req = $this->pdo->prepare("SELECT logiciel.identifiant, nom FROM ordinateurLogiciel JOIN logiciel ON ordinateurLogiciel.idLogiciel = logiciel.identifiant WHERE ordinateurLogiciel.idOrdinateur = ?");
        $req->execute(array($idOrdinateur));
        return $req->fetchAll();
    }

    /**
     * Récupère la liste des réservations en fonction des filtres
     * passés en paramètre exemple du contenu de $filtre :
     * ['reservation.dateDebut' => ['2021-10-01', PDO::PARAM_STR], ETC...]
     *
     * @param array $filtre Filtres de recherche
     * @return mixed Retourne la liste des réservations
     */
    public function getReservations($offset = 0, $filtre = [], $limit = null)
    {
        if (is_null($limit)) {
            $limit = Config::get('NB_LIGNES');
        }

        $sql = "SELECT
                    reservation.identifiant AS 'IDENTIFIANT_RESERVATION',
                    reservation.dateDebut AS 'DATE_DEBUT',
                    reservation.dateFin AS 'DATE_FIN',
                    reservation.description AS 'DESCRIPTION',
                    salle.nom AS 'NOM_SALLE',
                    activite.type AS 'TYPE_ACTIVITE',
                    individu.prenom AS 'PRENOM_EMPLOYE',
                    individu.nom AS 'NOM_EMPLOYE'
                FROM reservation
                JOIN salle 
                ON salle.identifiant = reservation.idSalle
                JOIN activite
                ON activite.identifiant = reservation.idActivite
                JOIN individu
                ON individu.identifiant = reservation.idEmploye";

        // Ajout des conditions de filtre
        if (!empty($filtre)) {
            $sql .= " WHERE ";
            $conditions = [];
            foreach ($filtre as $key => $value) {
                if (!empty($value)) {
                    $conditions[] = "$key = :" . str_replace('.', '_', $key);
                }
            }
            $sql .= implode(' AND ', $conditions);
        }

        $sql .= " ORDER BY reservation.identifiant ASC LIMIT :limit OFFSET :offset";

        $req = $this->pdo->prepare($sql);
        $req->bindParam(':limit', $limit, PDO::PARAM_INT);
        $req->bindParam(':offset', $offset, PDO::PARAM_INT);
        foreach ($filtre as $key => $value) {
            if (!empty($value)) {
                $req->bindParam(":" . str_replace('.', '_', $key), $value[0], $value[1]);
            }
        }

        $req->execute();
        return $req->fetchAll();
    }

    /**
     * Récupère le nombre total de réservations
     * @return mixed Retourne le nombre total de réservations
     */
    public function getNbReservations()
    {
        $req = $this->pdo->query("SELECT COUNT(*) FROM reservation");
        return $req->fetchColumn();
    }

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
        $reqIndividu = $this->pdo->prepare("INSERT INTO individu (nom, prenom, telephone) VALUES (?, ?, ?)");
        $reqIndividu->execute([$nomEmploye, $prenomEmploye, $telephoneEmploye]);

        // Récupérer l'identifiant de l'individu récemment inséré
        $idIndividu = $this->pdo->lastInsertId();

        // Insérer dans la table utilisateur
        $reqUtilisateur = $this->pdo->prepare("INSERT INTO utilisateur (identifiant, motDePasse, role, individu) VALUES (?, ?, ?, ?)");
        $reqUtilisateur->execute([$identifiantEmploye, $motDePasseEmploye, 0, $idIndividu]);
    }

    /**
     * Permet de récupérer un identifiant de réservation pour un employé si il y en a un
     * @param $idEmploye int l'identifiant de l'employé
     * @return bool renvoie true si il y a un resultat sinon ne renvoie rien
     */
    public function verifReservationEmploye($idEmploye)
    {
        $req = $this->pdo->prepare("SELECT identifiant FROM reservation WHERE idEmploye = ?");
        $req->execute(array($idEmploye));

        return $req->rowCount() > 0;
    }

    /**
     * Permet de supprimer un employé de la base de données
     * @param $idEmploye int l'identifiant de l'employé
     * @return bool true si les suppression son bien effectuer
     */
    public function suppressionEmploye($idEmploye){
        try {

            // Suppression de l'utilisateur`
            $req = $this->pdo->prepare("DELETE FROM utilisateur WHERE individu = ?");
            $utilisateurs = $req->execute([$idEmploye]);

            // Suppression de l'individu
            $req1 = $this->pdo->prepare("DELETE FROM individu WHERE identifiant = ?");
            $individus = $req1->execute([$idEmploye]);

            return $individus && $utilisateurs;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

}

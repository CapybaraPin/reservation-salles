<?php

namespace controllers;

use services\Activite;
use services\Auth;
use services\Config;
use services\Database;
use services\Employe;
use services\Ordinateur;
use services\Reservation;
use services\Salle;

/**
 * Contrôleur général de l'application. Permet de faire une passerelle
 * entre tout les controleurs pour des fonctions communes.
 */
class Controller
{

    protected Auth $authModel;
    protected Config $configModel;
    protected Database $databaseModel;
    protected Employe $employeModel;
    protected Ordinateur $ordinateurModel;
    protected Salle $salleModel;
    protected Activite $activiteModel;
    protected Reservation $reservationModel;

    protected $pdo;

    /**
     * Constructeur du contrôleur général.
     */
    public function __construct()
    {
        date_default_timezone_set('Europe/Paris');

        $this->authModel = new Auth();
        $this->configModel = new Config();
        $this->databaseModel = new Database();
        $this->employeModel = new Employe();
        $this->ordinateurModel = new Ordinateur();
        $this->salleModel = new Salle();
        $this->activiteModel = new Activite();
        $this->reservationModel = new Reservation();
        
    }

    /**
     * Permet la deconnexion de l'utilisateur
     * @return void
     */
    public function deconnexion()
    {
        if (isset($_POST["deconnexion"]))
        {
            $this->authModel->deconnexion();
        }
    }

    /**
     * Gère la pagination en fonction des paramètres fournis.
     *
     * @param int $nbElements Le nombre total d'éléments.
     * @return array int La page actuelle, limitée entre 1 et le nombre maximal de pages,
     *               int Le nombre maximal de pages.
     */
    public function getPagination(int $nbElements) {
        $page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
        $page = max(intval($page), 1);
        $pageMax = ceil($nbElements / Config::get('NB_LIGNES'));
        return [min($page, $pageMax), $pageMax];
    }
}
<?php

namespace controllers;

use services\Activite;
use services\Auth;
use services\Config;
use services\Individu;
use services\Exportation;
use services\Ordinateur;
use services\Organisme;
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

    protected Individu $employeModel;
    protected Ordinateur $ordinateurModel;
    protected Salle $salleModel;
    protected Activite $activiteModel;
    protected Reservation $reservationModel;

    protected Organisme $organismeModel;

    protected Exportation $exportationModel;

    /**
     * Constructeur du contrôleur général.
     */
    public function __construct()
    {
        date_default_timezone_set('Europe/Paris');

        $this->authModel = new Auth();
        $this->configModel = new Config();
        $this->employeModel = new Individu();
        $this->ordinateurModel = new Ordinateur();
        $this->salleModel = new Salle();
        $this->activiteModel = new Activite();
        $this->reservationModel = new Reservation();
        $this->organismeModel = new Organisme();
        $this->exportationModel = new Exportation();
    }

    /**
     * Permet la deconnexion de l'utilisateur
     * @return void
     */
    public function deconnexion() {
        if (isset($_POST["deconnexion"])) {
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
        $pageMax = max($pageMax, 1);
        return [min($page, $pageMax), $pageMax];
    }
}
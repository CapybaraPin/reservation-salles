<?php

namespace controllers;

use services\Auth;
use services\Config;

/**
 * Contrôleur général de l'application. Permet de faire une passerelle
 * entre tout les controleurs pour des fonctions communes.
 */
class Controller
{
    /**
     * Permet la deconnexion de l'utilisateur
     * @return void
     */
    public function deconnexion()
    {
        if (isset($_POST["deconnexion"]))
        {
            $auth = new Auth();
            $auth->deconnexion();
        }
    }

    /**
     * Gère la pagination en fonction des paramètres fournis.
     *
     * @param int $nbReservations Le nombre total de réservations.
     * @return array int La page actuelle, limitée entre 1 et le nombre maximal de pages,
     *               int Le nombre maximal de pages.
     */
    public function getPagination() {
        $page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
        $page = max(intval($page), 1);
        $pageMax = ceil($page / Config::get('NB_LIGNES'));
        return [min($page, $pageMax), $pageMax];
    }
}
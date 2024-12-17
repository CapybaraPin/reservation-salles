<?php

namespace controllers;

use services\Auth;

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
}
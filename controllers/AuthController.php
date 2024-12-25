<?php

namespace controllers;

use services\Auth;
use services\Config;

/**
 * Class AuthController
 *
 * Contrôleur qui permet la gestion des requêtes pour la page
 * d'authentification de l'application. C'est à dire la récupération
 * de la page ou une tentative de connexion.
 *
 * @package controllers
 */
class AuthController extends Controller
{
    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get()
    {
        require __DIR__ . '/../views/auth.php';
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post()
    {
        if (isset($_POST['identifiant']) && isset($_POST['motdepasse'])) {

            $identifiant = htmlspecialchars($_POST['identifiant']);
            $motDePasse = htmlspecialchars($_POST['motdepasse']);

            try {
                $this->authModel->connexion($identifiant, $motDePasse);
                header("Location: ".Config::get('APP_URL')."/");

            } catch (\Exception $e) {
                $message = $e->getMessage();
            }

            require __DIR__ . '/../views/auth.php';
        }
    }
}
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
class AuthController
{

    protected $auth;

    /**
     * AuthController constructeur
     *
     * Créer une instance de la classe permettant la gestion de l'authentification.
     */
    public function __construct()
    {
        $this->auth = new Auth();
    }

    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get()
    {
        if (isset($_SESSION['user'])) {
            header("Location: ".Config::get('APP_URL')."/");
        } else {
            require __DIR__ . '/../views/auth.php';
        }
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post()
    {
        if (isset($_POST['identifiant']) && isset($_POST['motdepasse'])) {

            $identifiant = htmlspecialchars($_POST['identifiant']);
            $motDePasse = htmlspecialchars($_POST['motdepasse']);

            if ($this->auth->connexion($identifiant, $motDePasse)) {
                header("Location: ".Config::get('APP_URL')."/");
            } else {
                $message = "L'adresse email ou le mot de passe est incorrect.";
            }

            require __DIR__ . '/../views/auth.php';
        }
    }
}
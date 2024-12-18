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
                // TODO : faire en sorte que je puisse afficher
                    // Si l'utilisateur n'a pas entré les bons identifiants
                    // Si l'utilisateur n'a pas entré tous les champs
                    // Si une erreur c'est produite lors de la connexion
                    // Si une erreur c'est produite lors de la connexion à la base de données
                    // Si l'utilisateur n'est pas associé à un individu

                $this->auth->connexion($identifiant, $motDePasse);
                header("Location: ".Config::get('APP_URL')."/");

            } catch (\Exception $e) {
                $message = $e->getMessage();
            }

            require __DIR__ . '/../views/auth.php';
        }
    }
}
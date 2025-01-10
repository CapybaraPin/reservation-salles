<?php

namespace controllers;

use Exception;
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

    private $message; // Message d'erreur

    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get()
    {
        $message = $this->message;
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
            $memoriser = isset($_POST['memoriser']) ? true : false;

            try {
                $this->authModel->connexion($identifiant, $motDePasse, $memoriser);
                header("Location: ".Config::get('APP_URL')."/");

            } catch (Exception $e) {
                $this->message = $e->getMessage();
            }

            $this->get();
        }
    }

    /**
     * Fonction pour gérer la connexion automatique par token
     */
    public function connexionToken() {
        if (isset($_COOKIE['authToken'])) {
            try {
                $token = htmlspecialchars($_COOKIE['authToken']);
                $this->authModel->connexionToken($token);
                header("Location: ".Config::get('APP_URL')."/");
            } catch (Exception $e) {
                $this->authModel->deconnexion();
                $this->message = $e->getMessage();
            }

            $this->get();
        }
    }
}
<?php

namespace services;

/**
 * Class Auth
 *
 * Cette classe fournit un moyen d'authentifier les utilisateurs.
 *
 * @package services
 */
class Auth
{
    private $MESSAGE_AUTH_SUCCESS = true;
    private $MESSAGE_AUTH_ERROR = false;

    /**
     * Connexion de l'utilisateur
     * @param string $identifiant
     * @param string $motDePasse
     * @return string Retourne un message d'erreur ou de succès.
     */
    public function connexion($identifiant, $motDePasse)
    {
        global $pdo;

        $req = $pdo->prepare("SELECT identifiant, motDePasse FROM utilisateur WHERE identifiant = :identifiant");
        $req->execute(['identifiant' => $identifiant]);
        $user = $req->fetch();

        // Si l'utilisateur existe et que le mot de passe est correct
        if ($user && password_verify($motDePasse, $user['motDePasse'])) {

            $_SESSION['user'] = $user;

            return $this->MESSAGE_AUTH_SUCCESS;

        } else {
            return $this->MESSAGE_AUTH_ERROR;
        }
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function deconnexion()
    {
        if (isset($_SESSION['user'])) {
            session_destroy();
            header("Location: ".Config::get('APP_URL')."/auth");
        }
    }
}
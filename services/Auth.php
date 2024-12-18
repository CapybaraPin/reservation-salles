<?php

namespace services;

use services\Utilisateur;

/**
 * Class Auth
 *
 * Cette classe fournit un moyen d'authentifier les utilisateurs.
 *
 * @package services
 */
class Auth
{
    private $MESSAGE_AUTH_SUCCESS = "Vous êtes bien connectés.";
    private $MESSAGE_AUTH_ERROR = "L'identifiant ou le mot de passe est incorrect.";

    /**
     * Connexion de l'utilisateur
     * @param string $identifiant
     * @param string $motDePasse
     * @return string Retourne un message d'erreur ou de succès.
     */
    public function connexion($identifiant, $motDePasse)
    {
        global $pdo;

        $req = $pdo->prepare("SELECT identifiant, motDePasse, role, individu FROM utilisateur WHERE identifiant = :identifiant");
        $req->execute(['identifiant' => $identifiant]);
        $user = $req->fetch();

        // Si l'utilisateur existe et que le mot de passe est correct
        if ($user && password_verify($motDePasse, $user['motDePasse'])) {

            $_SESSION['userIdentifiant'] = $user['identifiant'];
            $_SESSION['userRole'] = $user['role'];
            $_SESSION['userIndividuId'] = $user['individu'];

            $utilisateur = new Utilisateur($user['identifiant'], $user['motDePasse'], $user['role']);

            $userNom = $utilisateur->getNom();
            $_SESSION['userNom'] = $userNom[0]["nom"];

            $userPrenom = $utilisateur->getPrenom();
            $_SESSION['userPrenom'] = $userPrenom[0]["prenom"];

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
        if (isset($_SESSION['userIdentifiant'])) {
            session_destroy();
            header("Location: ".Config::get('APP_URL')."/auth");
        }
    }
}
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
        global $db;
        $pdo = $db->getPDO();

        $req = $pdo->prepare("SELECT identifiant, motdepasse FROM utilisateurs WHERE identifiant = :identifiant");
        $req->execute(['identifiant' => $identifiant]);
        $user = $req->fetch();

        // Si l'utilisateur existe et que le mot de passe est correct
        if ($user && password_verify($motDePasse, $user['motdepasse'])) {

            $_SESSION['user'] = $user;

            return $this->MESSAGE_AUTH_SUCCESS;

        } else {
            return $this->MESSAGE_AUTH_ERROR;
        }
    }
}
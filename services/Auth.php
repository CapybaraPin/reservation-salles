<?php

namespace services;

use Exception;

/**
 * Class Auth
 *
 * Cette classe fournit un moyen d'authentifier les utilisateurs.
 *
 * @package services
 */
class Auth
{

    /** Nom du cookie d'authentification */
    const COOKIE_NOM = "authToken";

    /**
     * Connexion de l'utilisateur
     * @param string $identifiant L'identifiant de l'utilisateur
     * @param string $motDePasse Le mot de passe de l'utilisateur
     * @return string Retourne un message d'erreur ou de succès.
     */
    public function connexion($identifiant, $motDePasse, $memoriser=false)
    {
        $pdo = Database::getPDO();

        if (empty($identifiant) || empty($motDePasse)) {
            throw new Exception("Veuillez entrer un identifiant et un mot de passe valide.");
        }

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

            // Si l'utilisateur a coché la case "Se souvenir de moi"
            if ($memoriser) {
                // Générer un token et le stocker dans la base de données
                $token = bin2hex(random_bytes(32));
                $expiration = date('Y-m-d H:i:s', time() + Config::get("COOKIE_EXPIRATION"));

                $req = $pdo->prepare("UPDATE utilisateur SET token = :token, tokenExpiration = :expiration WHERE identifiant = :identifiant");
                $req->execute(['token' => $token, 'expiration' => $expiration, 'identifiant' => $identifiant]);

                setcookie(self::COOKIE_NOM, $token, time() + Config::get("COOKIE_EXPIRATION"), "/");
            }
        } else {
            throw new Exception("L'identifiant ou le mot de passe est incorrect.");
        }
    }

    /**
     * Connexion de l'utilisateur à partir d'un token
     * @param string $token Le token de l'utilisateur
     * @throws Exception Si le token est invalide ou expiré
     */
    public function connexionToken($token)
    {
        $pdo = Database::getPDO();

        $req = $pdo->prepare("SELECT identifiant, motDePasse, role, individu, tokenExpiration FROM utilisateur WHERE token = :token");
        $req->execute(['token' => $token]);
        $user = $req->fetch();

        if ($user) {
            if ($user['tokenExpiration'] > date('Y-m-d H:i:s')) {
                $_SESSION['userIdentifiant'] = $user['identifiant'];
                $_SESSION['userRole'] = $user['role'];
                $_SESSION['userIndividuId'] = $user['individu'];

                $utilisateur = new Utilisateur($user['identifiant'], $user['motDePasse'], $user['role']);

                $userNom = $utilisateur->getNom();
                $_SESSION['userNom'] = $userNom[0]["nom"];

                $userPrenom = $utilisateur->getPrenom();
                $_SESSION['userPrenom'] = $userPrenom[0]["prenom"];
            } else {
                throw new Exception("Votre session a expiré. Veuillez vous connecter à nouveau.");
            }
        } else {
            throw new Exception("Votre session a expiré. Veuillez vous connecter à nouveau.");
        }
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function deconnexion()
    {
        setcookie(self::COOKIE_NOM, "", 1, "/");
        if (isset($_SESSION['userIdentifiant'])) {
            session_destroy();
            header("Location: ".Config::get('APP_URL')."/auth");
        }
    }
}
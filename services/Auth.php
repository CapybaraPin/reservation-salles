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
    /**
     * Connexion de l'utilisateur
     * @param string $identifiant L'identifiant de l'utilisateur
     * @param string $motDePasse Le mot de passe de l'utilisateur
     * @return string Retourne un message d'erreur ou de succès.
     */
    public function connexion($identifiant, $motDePasse)
    {
        $pdo = Database::getPDO();

        if (empty($identifiant) || empty($motDePasse)) {
            throw new \Exception("Veuillez entrer un identifiant et un mot de passe valide.");
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

        } else {
            throw new \Exception("L'identifiant ou le mot de passe est incorrect.");
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
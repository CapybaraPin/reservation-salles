<?php

namespace services;

/**
 * Class Utilisateur
 *
 * Cette classe permet de gérer les utilisateurs de l'application.
 *
 * @package services
 */
class Utilisateur
{

    /** @var String Identifiant de l'utilisateur */
    private String $identifiant;

    /** @var string Mot de passe de l'utilisateur */
    private string $motDePasse;

    /** @var int Identifiant du grade de l'utilisateur */
    private int $gradeId;

    /**
     * Utilisateur contrôleur
     *
     * @param String $identifiant Identifiant de l'utilisteur
     * @param string $motDePasse Mot de passe de l'utilisateur
     * @param int $gradeId Identifiant du grade de l'utilisateur
     */
    public function __construct(String $identifiant, string $motDePasse, int $gradeId)
    {
        $this->identifiant = $identifiant;
        $this->motDePasse = $motDePasse;
        $this->gradeId = $gradeId;
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function deconnexion()
    {
        if (isset($_SESSION['user'])) {
            session_destroy();
            header("Location: ".Config::get('APP_URL')."/connexion");
        }
    }
}
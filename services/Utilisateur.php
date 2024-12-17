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

    /** @var int Identifiant du rôle de l'utilisateur */
    private int $roleId;

    /**
     * Utilisateur contrôleur
     *
     * @param String $identifiant Identifiant de l'utilisteur
     * @param string $motDePasse Mot de passe de l'utilisateur
     * @param int $roleId Identifiant du rôle de l'utilisateur
     */
    public function __construct(String $identifiant, string $motDePasse, int $roleId)
    {
        $this->identifiant = $identifiant;
        $this->motDePasse = $motDePasse;
        $this->roleId = $roleId;
    }

}
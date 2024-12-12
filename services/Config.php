<?php

namespace services;

use Dotenv\Dotenv;

/**
 * Classe Config
 *
 * Cette classe permet de charger et d'accéder aux paramètres de configuration provenant d'un fichier `.env`.
 *
 * Elle utilise la bibliothèque `Dotenv` pour charger automatiquement les variables d'environnement
 * à partir d'un fichier `.env` situé à la racine du projet.
 *
 * @package services
 */
class Config
{
    private static $dotenv = null;

    /**
     * Charge les paramètres de configuration à partir du fichier `.env`.
     *
     * @return void
     */
    public static function load()
    {
        // Vérifie si l'instance de Dotenv a déjà été créée
        if (self::$dotenv === null) {
            // Crée une instance de Dotenv et charge les variables d'environnement
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();
            self::$dotenv = $dotenv;
        }
    }

    /**
     * Obtient une variable de configuration par sa clé.
     *
     * Cette méthode permet d'obtenir la valeur d'une variable d'environnement (paramètre de
     * configuration) à partir du fichier `.env`. Si la clé n'existe pas dans le fichier, la méthode
     * retourne `null`.
     *
     * @param string $cle La clé de la variable de configuration.
     * @return string|null La valeur de la configuration, ou `null` si la clé n'existe pas.
     */
    public static function get($cle)
    {
        // Charge les paramètres de configuration si ce n'est pas déjà fait
        self::load();

        // Retourne la valeur de la clé dans le tableau $_ENV, ou null si la clé n'existe pas
        return isset($_ENV[$cle]) ? $_ENV[$cle] : null;
    }
}

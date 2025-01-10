<?php

namespace services;

use PDO;
use PDOException;

/**
 * Classe Database
 *
 * Cette classe permet d'établir une connexion avec une base de données MySQL en utilisant PDO.
 * Elle fournit une méthode pour obtenir une instance PDO configurée et prête à l'emploi.
 *
 * @package services
 */
class Database
{

    /**
     * Instance unique de la classe PDO.
     *
     * @var PDO|null
     */
    private static $pdo;


    /**
     * Constructeur de la classe Database.
     */
    private function __construct()
    {
        $this->initialiserPDO();
    }

    /**
     * Méthode statique pour obtenir une instance unique PDO
     *
     * @return PDO Retourne une instance de la classe Database.
     */
    public static function getPDO()
    {
        if (self::$pdo === null) {
            new Database();
        }

        return self::$pdo;
    }

    /**
     * Établit une connexion à la base de données et retourne l'instance PDO.
     *
     * Cette méthode utilise les informations de configuration (hôte, nom de la base de données,
     * charset, utilisateur et mot de passe) récupérées à partir du fichier `.env` via la classe `Config`.
     * Si la connexion échoue, une exception PDOException est levée.
     *
     * @throws PDOException Si la connexion échoue, une exception est lancée.
     */
    private function initialiserPDO()
    {
        try {
            // Récupère les informations de configuration depuis le fichier .env
            $host = Config::get('DB_HOST');
            $dbname = Config::get('DB_NAME');
            $charset = Config::get('DB_CHARSET');
            $user = Config::get('DB_USER');
            $password = Config::get('DB_PASSWORD');

            // Options de configuration de PDO
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password, $options);
            self::$pdo->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
}

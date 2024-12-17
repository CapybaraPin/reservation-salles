<?php

/*
 * Réservation de salle            SAE S3.A.02
 * IUT de RODEZ, tous les droits sont réservés
 *
 * Système de routage des pages de l'application
 *
 * Ce fichier permet de définir les routes de l'application
 * et de les associer à des contrôleurs qui se chargeront
 * de traiter les requêtes.
 *
 * Documentation de Bramus Router : https://github.com/bramus/router
 */

// Autoload de Composer
require __DIR__ . '/vendor/autoload.php';

// Récupération des classes du modèle
require __DIR__ . '/services/Database.php';
require __DIR__ . '/services/Config.php';
require __DIR__ . '/services/Auth.php';

// Récupération des classes des contrôleurs
require __DIR__ . '/controllers/AuthController.php';
require __DIR__ . '/controllers/AccueilController.php';
require __DIR__ . '/controllers/SallesController.php';
require __DIR__ . '/controllers/ReservationsController.php';
require __DIR__ . '/controllers/EmployesController.php';

// Import des classes
use controllers\AuthController;
use controllers\AccueilController;
use controllers\SallesController;
use controllers\ReservationsController;
use controllers\EmployesController;
use services\Database;

// Création d'une instance de Router
$router = new \Bramus\Router\Router();

// Connexion à la base de données
try {
    $db = new Database();
    $pdo = $db->getPDO();
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

session_start();

// Définition des routes
$router->get('/auth', [new AuthController(), 'get']);
$router->post('/auth', [new AuthController(), 'post']);

// Définition des routes pour l'accueil
$router->get('/', [new AccueilController(), 'get']);
$router->post('/', [new AccueilController(), 'post']);

// Défintion des routes pour les salles
$router->get('/salles', [new SallesController(), 'get']);
$router->post('/salles', [new SallesController(), 'post']);

// Définition des routes pour les réservations
$router->get('/reservations', [new ReservationsController(), 'get']);
$router->post('/reservations', [new ReservationsController(), 'post']);

// Définition des routes pour les employés
$router->get('/employes', [new EmployesController(), 'get']);
$router->post('/employes', [new EmployesController(), 'post']);


// Démarrage du routeur
$router->run();
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
require __DIR__ . '/services/Utilisateur.php';

// Récupération des classes des contrôleurs
require __DIR__ . '/controllers/Controller.php';
require __DIR__ . '/controllers/AuthController.php';
require __DIR__ . '/controllers/AccueilController.php';
require __DIR__ . '/controllers/SallesController.php';
require __DIR__ . '/controllers/ReservationsController.php';
require __DIR__ . '/controllers/EmployesController.php';
require __DIR__ . '/controllers/ActivitesController.php';


// Import des classes
use services\Database;
use controllers\AuthController;
use controllers\AccueilController;
use controllers\SallesController;
use controllers\ReservationsController;
use controllers\EmployesController;
use controllers\ActivitesController;

// Création d'une instance de Router
$router = new \Bramus\Router\Router();

// Connexion à la base de données
try {
    $db = new Database();
    $pdo = $db->getPDO();
} catch (PDOException $e) {
    $message = "<b>Erreur de connexion à la base de données</b><br>Détails : " . $e->getMessage();
    require __DIR__ . '/views/errors/500.php';
}

session_start();

// Middleware pour vérifier la connexion de l'utilisateur
$router->before('GET|POST', '/(?!auth).*', function() {
    if (!isset($_SESSION['userIdentifiant'])) {
        // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
        header('Location: /auth');
        exit();
    }
});

// Définition des routes pour la connexion
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

// Définition des routes pour les activités
$router->get('/activites', [new ActivitesController(), 'get']);
$router->post('/activites', [new ActivitesController(), 'post']);

// Défintion de la routeur pour l'erreur 404
$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    require __DIR__ . '/views/errors/404.php';
});


// Démarrage du routeur
$router->run();
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

// Vérification de la version de PHP
if (version_compare(PHP_VERSION, '5.3', '<')) {
    die('Erreur : Ce script nécessite PHP 5.3 ou une version supérieure. Votre version actuelle est : ' . PHP_VERSION);
}

// Autoload de Composer
require __DIR__ . '/vendor/autoload.php';

// Récupération des exceptions
require __DIR__ . '/services/exceptions/FieldValidationException.php';

// Récupération des classes du modèle
require __DIR__ . '/services/Database.php';
require __DIR__ . '/services/Config.php';
require __DIR__ . '/services/Auth.php';
require __DIR__ . '/services/Utilisateur.php';
require __DIR__ . '/services/Employe.php';
require __DIR__ . '/services/Reservation.php';
require __DIR__ . '/services/Salle.php';
require __DIR__ . '/services/Activite.php';
require __DIR__ . '/services/Ordinateur.php';
require __DIR__ . '/services/SQLHelper.php';
require __DIR__ . '/services/Organisme.php';
require __DIR__ . '/services/Exportation.php';

// Récupération des classes des contrôleurs
require __DIR__ . '/controllers/Controller.php';
require __DIR__ . '/controllers/FiltresController.php';
require __DIR__ . '/controllers/AuthController.php';
require __DIR__ . '/controllers/SallesController.php';
require __DIR__ . '/controllers/ModifierEmployesController.php';
require __DIR__ . '/controllers/InformationSalleController.php';
require __DIR__ . '/controllers/ReservationsController.php';
require __DIR__ . '/controllers/AccueilController.php';
require __DIR__ . '/controllers/EmployesController.php';
require __DIR__ . '/controllers/ActivitesController.php';
require __DIR__ . '/controllers/ExportController.php';

// Import des classes
use services\Database;
use controllers\AuthController;
use controllers\SallesController;
use controllers\InformationSalleController;
use controllers\ReservationsController;
use controllers\AccueilController;
use controllers\EmployesController;
use controllers\ActivitesController;
use controllers\ExportController;
use controllers\ModifierEmployesController;


// Création d'une instance de Router
$router = new \Bramus\Router\Router();

// Essaie de connexion à la base de données
try {
    Database::getPDO(); // jamais utilisé
} catch (PDOException $e) {
    $message = "<b>Erreur de connexion à la base de données</b><br>Détails : " . $e->getMessage();
    require __DIR__ . '/views/errors/500.php';
}

session_start();

// Middleware pour vérifier la connexion de l'utilisateur
$router->before('GET|POST', '/(?!auth).*', function() {
    if (!isset($_SESSION['userIdentifiant'])) {
        if (isset($_COOKIE['authToken'])) {
            $auth = new AuthController();
            $auth->connexionToken();
        } else {
            // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
            header('Location: /auth');
        }
        exit();
    }
});

// Définition des routes pour la connexion
$router->get('/auth', [new AuthController(), 'get']);
$router->post('/auth', [new AuthController(), 'post']);

// Définition des routes pour l'accueil
$router->get('/', [new AccueilController(), 'get']);
$router->post('/', [new AccueilController(), 'post']);

/*
 * Définition des routes pour les salles
 */

// Liste des salles
$router->get('/salles', [new SallesController(), 'get']);
$router->post('/salles', [new SallesController(), 'post']);

// Visualisation d'une salle
$router->get('/salle/{salleId}/view', function($salleId) {
    $salleController = new InformationSalleController();
    $salleController->get($salleId, "view");
});

$router->post('/salle/{salleId}/view', function($salleId) {
    $salleController = new InformationSalleController();
    $salleController->post($salleId, 'view');
});

// Modification d'une salle
$router->get('/salle/{salleId}/edit', function($salleId) {
    $salleController = new InformationSalleController();
    $salleController->get($salleId, "edit");
});

$router->post('/salle/{salleId}/edit', function($salleId) {
    $salleController = new InformationSalleController();
    $salleController->post($salleId, "edit");
});

/*
 * Définition des routes pour les réservations
 */

// Liste des réservations
$router->get('/reservations', [new ReservationsController(), 'get']);
$router->post('/reservations', [new ReservationsController(), 'post']);


// Visualisation d'une réservation
$router->get('/reservations/{reservationId}/view', function($reservationId) {
    $reservationsController = new ReservationsController();
    $reservationsController->consultationReservation($reservationId);
});


// Définition des routes pour les employés
$router->get('/employes', [new EmployesController(), 'get']);
$router->post('/employes', [new EmployesController(), 'post']);

// Modification d'un employé
$router->get('/employe/{employeId}/edit', function($employeId) {
    $employeController = new ModifierEmployesController();
    $employeController->get($employeId, "edit");
});

$router->post('/employe/{employeId}/edit', function($employeId) {
    $employeController = new ModifierEmployesController();
    $employeController->post($employeId, "edit");
});

// Définition des routes pour les activités
$router->get('/activites', [new ActivitesController(), 'get']);
$router->post('/activites', [new ActivitesController(), 'post']);

/*
 * Définition des routes pour l'exportation des données
 */
$router->get('/exportation', [new ExportController(), 'get']);
$router->get('/exportation/telecharger', [new ExportController(), 'exportation']);

// Défintion de la routeur pour l'erreur 404
$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    require __DIR__ . '/views/errors/404.php';
});

// Démarrage du routeur
$router->run();
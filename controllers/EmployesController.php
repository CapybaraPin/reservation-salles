<?php

namespace controllers;

use services\Auth;
use services\Config;

/**
 * Contrôleur pour la page des employés
 */
class EmployesController extends Controller
{
    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get()
    {
        // Récupération de la liste des employés
        global $db;
        $employes = $db->getEmployes();

        $reservations = [];
        foreach ($employes as $employe) {
            $hasReservation = $db->verfiUserReservation($employe['IDENTIFIANT_EMPLOYE']);
            $reservations[$employe['IDENTIFIANT_EMPLOYE']] = $hasReservation;
        }

        require __DIR__ . '/../views/employes.php';
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post()
    {
        global $db;

        try {
            $success = $this->ajouterEmploye();
        } catch (\Exception $e) {
            $erreur = "Erreur lors de l'ajout de l'employé : " . $e->getMessage();
        }

        // Vérifie si une demande de suppression est envoyée
        if (isset($_POST['deleteEmploye']) && isset($_POST['employeId']) && is_numeric($_POST['employeId'])) {
            $idEmploye = intval($_POST['employeId']);

            // Appelle la méthode pour supprimer l'employé
            $result = $db->deleteEmploye($idEmploye);

        }
        $employes = $db->getEmployes();

        $this->deconnexion();
        $employes = $db->getEmployes();
        require __DIR__ . '/../views/employes.php';
    }

    /**
     * Fonction qui gère l'ajout d'un employé
     */
    public function ajouterEmploye()
    {
        global $db;

        if (isset($_POST["nom"])
            && isset($_POST["prenom"])
            && isset($_POST["telephone"])
            && isset($_POST["identifiant"])
            && isset($_POST["motdepasse"])) {

            $nomEmploye = htmlspecialchars($_POST["nom"]);
            $prenomEmploye = htmlspecialchars($_POST["prenom"]);
            $telephoneEmploye = htmlspecialchars($_POST["telephone"]);
            $identifiantEmploye = htmlspecialchars($_POST["identifiant"]);

            // Chiffrement du mot de passe
            $motDePasseEmploye = password_hash($_POST["motdepasse"], PASSWORD_DEFAULT); // TODO : Comment afficher le mot de passe à l'administrateur ?

            // Vérification du format du numéro de téléphone
            if (!preg_match('/^(?:\+33|0)[1-9](?:[\d]{2}){4}$/', $telephoneEmploye)) {
                throw new \Exception("Le numéro de téléphone n'est pas valide. Veuillez entrer un numéro de téléphone français correct.");
            }

            // Vérification que les champs ne soient pas vides
            if (!empty($nomEmploye) && !empty($prenomEmploye) && !empty($telephoneEmploye) && !empty($identifiantEmploye) && !empty($motDePasseEmploye)) {
                $db->ajouterEmploye($nomEmploye, $prenomEmploye, $telephoneEmploye, $identifiantEmploye, $motDePasseEmploye);
            } else {
                throw new \Exception("Veuillez remplir tous les champs");
            }

            return "Vous avez bien ajouté un employé! (" . $nomEmploye . " " . $prenomEmploye . ")";
        }
    }
}
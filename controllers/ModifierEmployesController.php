<?php

namespace controllers;

/**
 * Contrôleur pour la page des information et modification d'une salle
 */
class ModifierEmployesController extends FiltresController {

    /**
     * Variables pour les messages d'erreur d'ajout
     */
    private $erreurs;

    /**
     * Variables pour les messages de succès d'ajout
     */
    private $success;

    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get($employeID = null, $action = null) {

        if ($action == 'edit')
        {
                $this->modifierEmploye($employeID);
        }
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post($employeID = null, $action = null) {

        $this->get($employeID, $action);
    }

    /**
     * Gère la modification d'un employé
     * @param int $employeId
     */
    public function modifierEmploye($employeId) {
        try {
            $employe = $this->employeModel->getEmploye($employeId);
        } catch (\Exception $e) {
            header('HTTP/1.1 404 Not Found');
            require_once __DIR__ . "/../views/errors/404.php";
            return;
        }

        if (isset($_POST['nom'], $_POST['prenom'], $_POST['telephone'])) {
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $telephone = htmlspecialchars($_POST['telephone']);

            try {
                $this->employeModel->modifierEmploye($employeId, $nom, $prenom, $telephone);
                $_SESSION['messageValidation'] = "Les informations de l'employé ont été mises à jour avec succès.";
                header('Location: /employes');
                exit;
            } catch (\Exception $e) {
                $this->erreurs = $e->getMessage();
            }
        }

        if(isset($_SESSION['messageValidation'])) {
            $this->success = $_SESSION['messageValidation'];
            unset($_SESSION['messageValidation']);
        }
        if(isset($_SESSION['messageErreur'])) {
            $this->erreurs = $_SESSION['messageErreur'];
            unset($_SESSION['messageErreur']);
        }

        $erreurs = $this->erreurs;
        $success = $this->success;

        require __DIR__ . '/../views/modifierEmploye.php';
    }
}

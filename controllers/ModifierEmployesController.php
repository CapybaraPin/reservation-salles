<?php

namespace controllers;

use services\exceptions\FieldValidationException;

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

        if($_SESSION['userRole'] == '1') {
            if ($action == 'edit')
            {
                $this->modifierEmploye($employeID);
            }
        } else {
            header('HTTP/1.1 404 Not Found');
            require_once __DIR__ . "/../views/errors/404.php";
        }
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post($employeID = null, $action = null) {

        $this->deconnexion();

        $this->get($employeID, $action);
    }

    /**
     * Gère la modification d'un employé
     * @param int $employeId
     */
    public function modifierEmploye($employeId) {
        $hasAccount = true;
        try {
            $employe = $this->employeModel->getEmploye($employeId);
            $nom = $employe["NOM_EMPLOYE"];
            $prenom = $employe["PRENOM_EMPLOYE"];
            $telephone = $employe["TELEPHONE_EMPLOYE"];
            $id = $this->employeModel->getID($employeId)["identifiant"];

        } catch (\Exception $e) {
            header('HTTP/1.1 404 Not Found');
            require_once __DIR__ . "/../views/errors/404.php";
            return;
        }

        if (isset($_POST['nom'], $_POST['prenom'], $_POST['telephone'], $_POST['identifiant'])) {
            $nom = htmlspecialchars($_POST['nom'], ENT_NOQUOTES);
            $prenom = htmlspecialchars($_POST['prenom'], ENT_NOQUOTES);
            $telephone = htmlspecialchars($_POST['telephone']);

            $id = htmlspecialchars($_POST['identifiant'], ENT_NOQUOTES);

            if(!empty($_POST['motdepasse'])) {
                $motDePasseEmploye = password_hash($_POST["motdepasse"], PASSWORD_DEFAULT);
                $this->employeModel->modifierMotDePasse($employeId, $motDePasseEmploye);
            }

            try {
                $this->employeModel->modifierEmploye($employeId, $nom, $prenom, $telephone);
                $this->employeModel->modifierIdentifiant($employeId ,$id);
                $_SESSION['messageValidation'] = "Les informations de l'employé ont été mises à jour avec succès.";
                header('Location: /employes');
                exit;
            } catch (FieldValidationException $e) {
                $this->erreurs = $e->getErreurs();
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

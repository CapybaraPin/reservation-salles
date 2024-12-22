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
     * Variables pour les messages d'erreur d'ajout
     */
    private $erreur;

    /**
     * Variables pour les messages d'erreur de suppression
     */
    private $erreurSuppression;

    /**
     * Variables pour les messages de succès d'ajout
     */
    private $success;

    /**
     * Variables pour les messages de succès de suppression
     */
    private $suppression;


    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get()
    {
        // Récupération de la liste des employés
        global $db;

        $titre = 'Employés';
        $colonnes = [
            "IDENTIFIANT_EMPLOYE" => 'Identifiant',
            "NOM_EMPLOYE" => 'Nom',
            "PRENOM_EMPLOYE" => 'Prénom',
            "TELEPHONE_EMPLOYE" => 'Téléphone',
            ];

        list ($page, $pageMax) = $this->getPagination();
        $nbLignesPage = Config::get('NB_LIGNES');
        $employes = $db->getEmployes(($page - 1) * $nbLignesPage);
        $nbEmployes = $db->getNbEmployes();

        // ajout des employés ayant une réservation
        $reservations = [];
        foreach ($employes as $employe) {
            $hasReservation = $db->verifReservationEmploye($employe['IDENTIFIANT_EMPLOYE']);
            $reservations[$employe['IDENTIFIANT_EMPLOYE']] = $hasReservation;
        }

        // Création des actions pour chaque employé
        // et ajout des informations demandées par les colonnes
        $actions = [];
        foreach ($employes as &$employe) {
            $employe['ID'] = $employe['IDENTIFIANT_EMPLOYE'];

            $actions[$employe['IDENTIFIANT_EMPLOYE']]['info'] = [
                'attributs' => ['class' => 'btn btn-nav', 'title' => 'Plus d\'informations'],
                'icone' => 'fa-solid fa-circle-info'
            ];

            if ($_SESSION['userRole'] == '1') {
                $actions[$employe['IDENTIFIANT_EMPLOYE']]['modifier'] = [
                    'attributs' => ['class' => 'btn', 'title' => 'Modifier'],
                    'icone' => 'fa-solid fa-pen'
                ];

                $actions[$employe['IDENTIFIANT_EMPLOYE']]['supprimer'] = [
                    'attributs' => ['class' => 'btn btn-nav', 'title' => 'Supprimer',
                                    'data-reservation' => isset($reservations[$employe["IDENTIFIANT_EMPLOYE"]])
                                                          && $reservations[$employe["IDENTIFIANT_EMPLOYE"]] ? 'true' : 'false',
                                    'href' => '#' . $employe['IDENTIFIANT_EMPLOYE']
                                    ],
                    'icone' => 'fa-solid fa-trash-can'
                ];
            }
        }

        $erreur = $this->erreur;
        $erreurSuppression = $this->erreurSuppression;
        $success = $this->success;
        $suppression = $this->suppression;

        require __DIR__ . '/../views/employes.php';
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post()
    {
        global $db;

        try {
            $this->success = $this->ajouterEmploye();
        } catch (\Exception $e) {
            $this->erreur = "Erreur lors de l'ajout de l'employé : " . $e->getMessage();
        }

        // Vérifier si une demande de suppression est effectuée
        if (isset($_POST['supprimerEmploye']) && isset($_POST['employeId'])) {
            try {
                $this->suppression = $this->supprimerEmploye();
            } catch (\Exception $e) {
                $this->erreurSuppression = "Erreur lors de la suppression de l'employé : " . $e->getMessage();
            }
        }

        $this->deconnexion();

        $this->get();
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

    public function supprimerEmploye()
    {
        global $db;

        if (isset($_POST['supprimerEmploye']) && isset($_POST['employeId']) && is_numeric($_POST['employeId'])) {
            $idEmploye = intval($_POST['employeId']); // Conversion sécurisée en entier

            try {
                // Appelle la méthode pour supprimer l'employé
                $result = $db->suppressionEmploye($idEmploye);

                if ($result) {
                    return "L'employé avec l'ID $idEmploye a été supprimé avec succès.";
                } else {
                    throw new \Exception("La suppression de l'employé a échoué. Veuillez réessayer.");
                }
            } catch (\Exception $e) {
                // En cas d'exception, enregistrer l'erreur et retourner un message
                throw new \Exception("Une erreur s'est produite lors de la suppression de l'employé.");
            }
        } else {
            throw new \Exception("Données invalides. Veuillez vérifier les informations soumises.");
        }
    }
}
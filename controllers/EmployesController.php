<?php

namespace controllers;

use Exception;
use PDO;
use services\Auth;
use services\Config;
use services\Database;

/**
 * Contrôleur pour la page des employés
 */
class EmployesController extends FiltresController
{
    private $success; // Pour gérer les messages de succès
    private $erreur;  // Pour gérer les messages d'erreur
    const FILTRES_DISPONIBLES = [
        'nom' => ['label' => 'Nom', 'type' => PDO::PARAM_STR, 'champ' => 'nom'],
        'prenom' => ['label' => 'Prénom', 'type' => PDO::PARAM_STR, 'champ' => 'prenom'],
        'telephone' => ['label' => 'Téléphone', 'type' => PDO::PARAM_STR, 'champ' => 'telephone'],
    ];

    const TITRE = 'Employés';
    const COLONNES = [
        "IDENTIFIANT_EMPLOYE" => 'Identifiant',
        "NOM_EMPLOYE" => 'Nom',
        "PRENOM_EMPLOYE" => 'Prénom',
        "TELEPHONE_EMPLOYE" => 'Téléphone',
    ];

    public function get()
    {
        $filtresDisponibles = self::FILTRES_DISPONIBLES;
        $this->setFiltresDisponibles($filtresDisponibles);
        $filtres = $this->getFiltres();

        $titre = self::TITRE;
        $colonnes = self::COLONNES;

        $filtresRequete = $this->getFiltresRequete();
        $nbEmployes = $this->employeModel->getNbEmployes($filtresRequete);

        // Si aucun employé n'est trouvé
        if ($nbEmployes === 0) {
            $alerte = "Aucun employé trouvé pour les critères spécifiés.";
        }

        list($page, $pageMax) = $this->getPagination($nbEmployes);
        $nbLignesPage = Config::get('NB_LIGNES');
        $employes = $this->employeModel->getEmployes(($page - 1) * $nbLignesPage, $filtresRequete);

        // ajout des employés ayant une réservation
        $reservations = [];
        foreach ($employes as $employe) {
            $hasReservation = $this->employeModel->verifReservationEmploye($employe['IDENTIFIANT_EMPLOYE']);
            $reservations[$employe['IDENTIFIANT_EMPLOYE']] = $hasReservation;
        }

        // Création des actions pour chaque employé
        // et ajout des informations demandées par les colonnes
        $actions = [];
        foreach ($employes as &$employe) {
            $employe['ID'] = $employe['IDENTIFIANT_EMPLOYE'];

            if($_SESSION['userRole'] == '1') {

                $actions[$employe['IDENTIFIANT_EMPLOYE']]['modifier'] = [
                    'attributs' => ['href' => '/employe/'.$employe["ID"].'/edit', 'class' => 'btn', 'title' => 'Modifier'],
                    'icone' => 'fa-solid fa-pen'
                ];

                $actions[$employe['IDENTIFIANT_EMPLOYE']]['supprimer'] = [
                    'attributs' => ['class' => 'btn btn-nav', 'title' => 'SupprimerEmploye',
                        'data-reservation' => isset($reservations[$employe["IDENTIFIANT_EMPLOYE"]])
                        && $reservations[$employe["IDENTIFIANT_EMPLOYE"]] ? 'true' : 'false',
                        'href' => '#' . $employe['IDENTIFIANT_EMPLOYE']
                    ],
                    'icone' => 'fa-solid fa-trash-can'
                ];
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

        $erreur = $this->erreur;
        $success = $this->success;

        require __DIR__ . '/../views/employes.php';
    }

    public function post()
    {
        $this->setFiltres($_POST['filtres'] ?? []);
        $filtresDisponibles = self::FILTRES_DISPONIBLES;
        $this->setFiltresDisponibles($filtresDisponibles);
        if (isset($_POST['ajouter_filtre'])) {
            $this->ajouterFiltre($_POST['nouveau_filtre']);
        } elseif (isset($_POST['supprimer_filtre'])) {
            $this->supprimerFiltre($_POST['supprimer_filtre']);
        }
        try {
            $this->success = $this->ajouterEmploye();
        } catch (Exception $e) {
            $this->erreur = "Erreur lors de l'ajout de l'employé : " . $e->getMessage();
        }

        // Vérifier si une demande de suppression est effectuée
        if (isset($_POST['supprimerEmploye']) && isset($_POST['employeId'])) {
            try {
                $this->success = $this->supprimerEmploye();
            } catch (Exception $e) {
                $this->erreur = "Erreur lors de la suppression de l'employé : " . $e->getMessage();
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
        if (isset($_POST["nom"])
            && isset($_POST["prenom"])
            && isset($_POST["telephone"])
            && isset($_POST["identifiant"])
            && isset($_POST["motdepasse"])) {

            $nomEmploye = htmlspecialchars($_POST["nom"], ENT_NOQUOTES);
            $prenomEmploye = htmlspecialchars($_POST["prenom"], ENT_NOQUOTES);
            $telephoneEmploye = htmlspecialchars($_POST["telephone"]);
            $identifiantEmploye = htmlspecialchars($_POST["identifiant"], ENT_NOQUOTES);

            // Chiffrement du mot de passe
            $motDePasseEmploye = password_hash($_POST["motdepasse"], PASSWORD_DEFAULT);

            // Vérification du format du numéro de téléphone
            if (!preg_match('/^\d{4}$/', $telephoneEmploye)) {
                throw new Exception("Le numéro de téléphone n'est pas valide. Veuillez entrer un numéro de téléphone correct.");
            }

            // Vérification que les champs ne soient pas vides
            if (!empty($nomEmploye) && !empty($prenomEmploye) && !empty($telephoneEmploye) && !empty($identifiantEmploye) && !empty($motDePasseEmploye)) {
                $this->employeModel->ajouterEmploye($nomEmploye, $prenomEmploye, $telephoneEmploye, $identifiantEmploye, $motDePasseEmploye);
            } else {
                throw new Exception("Veuillez remplir tous les champs");
            }

            return "Vous avez bien ajouté un employé! (" . $nomEmploye . " " . $prenomEmploye . ")";
        }
    }

    /**
     * Suppresion d'un employé lors du click sur le bouton de suppresion situé sur la page des employés
     * @return string
     * @throws Exception
     */
    public function supprimerEmploye()
    {
        if (isset($_POST['supprimerEmploye']) && isset($_POST['employeId']) && is_numeric($_POST['employeId'])) {
            $idEmploye = intval($_POST['employeId']); // Conversion sécurisée en entier

            try {
                // Appelle la méthode pour supprimer l'employé
                $result = $this->employeModel->suppressionEmploye($idEmploye);

                if ($result) {
                    return "L'employé avec l'ID $idEmploye a été supprimé avec succès.";
                } else {
                    throw new Exception("La suppression de l'employé a échoué. Veuillez réessayer.");
                }
            } catch (Exception $e) {
                // En cas d'exception, enregistrer l'erreur et retourner un message
                throw new Exception("Une erreur s'est produite lors de la suppression de l'employé.");
            }
        } else {
            throw new Exception("Données invalides. Veuillez vérifier les informations soumises.");
        }
    }
}
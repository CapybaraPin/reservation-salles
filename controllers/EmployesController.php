<?php

namespace controllers;

use PDO;
use services\Auth;
use services\Config;

/**
 * Contrôleur pour la page des employés
 */
class EmployesController extends FiltresController
{
    private $success; // Pour gérer les messages de succès
    private $erreur;  // Pour gérer les messages d'erreur
    const FILTRES_DISPONIBLES = [
        'nom' => ['label' => 'Nom', 'type' => PDO::PARAM_STR],
        'prenom' => ['label' => 'Prénom', 'type' => PDO::PARAM_STR],
        'telephone' => ['label' => 'Téléphone', 'type' => PDO::PARAM_STR],
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
            $alerte = "Aucun employé trouvé pour les critères spécifiés. Le tableau reste inchangé.";
            $filtresRequete = []; // Réinitialiser les filtres
            $nbEmployes = $this->employeModel->getNbEmployes([]); // Récupérer le nombre total d'employés
            $filtres = []; // Supprimer les filtres affichés
        } else {
            $alerte = null; // Pas d'alerte si des employés sont trouvés
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
                    'attributs' => ['class' => 'btn btn-nav', 'title' => 'SupprimerEmploye',
                        'data-reservation' => isset($reservations[$employe["IDENTIFIANT_EMPLOYE"]])
                        && $reservations[$employe["IDENTIFIANT_EMPLOYE"]] ? 'true' : 'false',
                        'href' => '#' . $employe['IDENTIFIANT_EMPLOYE']
                    ],
                    'icone' => 'fa-solid fa-trash-can'
                ];
            }
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
        } catch (\Exception $e) {
            $this->erreur = "Erreur lors de l'ajout de l'employé : " . $e->getMessage();
        }

        // Vérifier si une demande de suppression est effectuée
        if (isset($_POST['supprimerEmploye']) && isset($_POST['employeId'])) {
            try {
                $this->success = $this->supprimerEmploye();
            } catch (\Exception $e) {
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
                $this->employeModel->ajouterEmploye($nomEmploye, $prenomEmploye, $telephoneEmploye, $identifiantEmploye, $motDePasseEmploye);
            } else {
                throw new \Exception("Veuillez remplir tous les champs");
            }

            return "Vous avez bien ajouté un employé! (" . $nomEmploye . " " . $prenomEmploye . ")";
        }
    }

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
<?php

namespace controllers;

use http\Env\Request;
use PDO;
use services\Auth;
use services\Config;

/**
 * Contrôleur pour la page des salles
 */
class SallesController extends FiltresController
{
    /**
     * Variables pour les messages d'erreur d'ajout
     */
    private $erreurs;

    /**
     * Variables pour les messages de succès d'ajout
     */
    private $success;

    /**
     * Liste des filtres disponibles pour les salles
     */
    const FILTRES_DISPONIBLES = [
        'salle.nom' => ['label' => 'Salle', 'type' => PDO::PARAM_STR],
        'salle.capacite' => ['label' => 'Capacité', 'type' => PDO::PARAM_INT, 'operateur' => '>='],
        'salle.videoProjecteur' => ['label' => 'Vidéo Projecteur', 'type' => PDO::PARAM_INT, 'operateur' => '='],
        'salle.ecranXXL' => ['label' => 'Écran XXL', 'type' => PDO::PARAM_INT, 'operateur' => '=']
    ];

    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get($salleId = null, $action = null)
    {
        if ($salleId) {
            switch ($action) {
                case 'view':
                    $this->consultationSalle($salleId);
                    break;
                case 'edit':
                    $this->modifierSalle($salleId);
                    break;
                default:
                    $this->listeSalles();
            }
        } else {
            $this->listeSalles();
        }
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post($salleId = null, $action = null)
    {
        if ($salleId){
            switch ($action) {
                case 'supprimer':
                    $this->supprimerSalle($salleId);
                    break;
                case 'edit':
                    $this->modifierSalle($salleId);
                    break;
                default:
                    $this->listeSalles();
            }
            $this->supprimerSalle($salleId);
        } else {
            $this->deconnexion();
            $this->ajouterSalle();

            $this->setFiltres($_POST['filtres'] ?? []);

            $filtresDisponibles = self::FILTRES_DISPONIBLES;
            $this->setFiltresDisponibles($filtresDisponibles);
            if (isset($_POST['ajouter_filtre'])) {
                $this->ajouterFiltre($_POST['nouveau_filtre']);
            } elseif (isset($_POST['supprimer_filtre'])) {
                $this->supprimerFiltre($_POST['supprimer_filtre']);
            }

            $this->listeSalles();
        }
    }

    /**
     * Fonction qui gère la consultation d'une salle
     * @param $salleId int Identifiant de la salle
     * @return void Affiche la page de consultation d'une
     */
    public function consultationSalle($salleId)
    {
        $salle = $this->salleModel->getSalle($salleId);

        try {
            $ordinateurs = $this->ordinateurModel->getOrdinateursSalle($salleId);
            $logiciels = $this->ordinateurModel->getLogicielsOrdinateur($salle['ID_ORDINATEUR']);
            $nbReservations = $this->reservationModel->getNbReservationsSalle($salleId);
        } catch (\Exception $e) {
            $groupeOrdinateur = null;
            $logiciels = null;
        }

        require __DIR__ . '/../views/consultationSalle.php';
    }

    /**
     * Fonction qui gère l'affichage de la liste des salles
     * @return void
     */
    public function listeSalles()
    {
        $filtresDisponibles = self::FILTRES_DISPONIBLES;
        $this->setFiltresDisponibles($filtresDisponibles);
        $filtres = $this->getFiltres();

        $titre = 'Salles';
        $colonnes = [
            "ID_SALLE" => 'Identifiant',
            "NOM_SALLE" => 'Nom',
            "CAPACITE" => 'Capacité',
            "VIDEO_PROJECTEUR" => 'Vidéo Projecteur',
            "ECRAN_XXL" => 'Ecran XXL',
        ];

        $filtreReq = $this->getFiltresRequete();
        $nbSalles = $this->salleModel->getNbSalles($filtreReq);
        list ($page, $pageMax) = $this->getPagination($nbSalles);
        $nbLignesPage = Config::get('NB_LIGNES');
        $salles = $this->salleModel->getSalles(($page - 1) * $nbLignesPage, $filtreReq);

        // Création des actions pour chaque salle
        // et ajout des informations demandées par les colonnes
        $actions = [];
        foreach ($salles as &$salle) {
            $salle['ID'] = $salle['ID_SALLE'];

            $salle['VIDEO_PROJECTEUR'] = $salle['VIDEO_PROJECTEUR'] == "1" ? 'Oui' : 'Non';
            $salle['ECRAN_XXL'] = $salle['ECRAN_XXL'] == "1" ? 'Oui' : 'Non';

            if ($salle['ID_ORDINATEUR'] != 0) {
                $actions[$salle['ID_SALLE']]['info'] = [
                    'attributs' => ['href' => '/salle/'.$salle["ID"].'/view', 'class' => 'btn btn-nav', 'title' => 'Plus d\'informations'],
                    'icone' => 'fa-solid fa-circle-info'
                ];
            } else {
                $actions[$salle['ID_SALLE']]['info'] = [
                    'attributs' => ['class' => 'btn btn-nav disabled', 'title' => 'Plus d\'informations', 'disabled' => 'disabled'],
                    'icone' => 'fa-solid fa-circle-info'
                ];
            }

            $actions[$salle['ID_SALLE']]['modifier'] = [
                'attributs' => ['href' => '/salle/'.$salle["ID"].'/edit', 'class' => 'btn', 'title' => 'Modifier'],
                'icone' => 'fa-solid fa-pen'
            ];

            $actions[$salle['ID_SALLE']]['supprimer'] = [
                'attributs' => ['class' => 'btn btn-nav', 'title' => 'Supprimer'],
                'icone' => 'fa-solid fa-trash-can'
            ];

        }

        $typesOrdinateur = $this->ordinateurModel->getTypesOrdinateur();
        $logiciels = $this->ordinateurModel->getLogiciels();

        require __DIR__ . '/../views/salles.php';
    }

    /**
     * Fonction qui gère l'ajout d'une salle
     */
    public function ajouterSalle()
    {
        // Récupération des données du formulaire
        if (isset($_POST['nom']) && isset($_POST['capacite']) && isset($_POST['typeOrdinateur'])) {

            $nom = htmlspecialchars($_POST['nom']);
            $capacite = (int)htmlspecialchars($_POST['capacite']);
            $videoProjecteur = isset($_POST['videoProjecteur']) ? 1 : 0;
            $nbOrdinateurs = isset($_POST['nbOrdinateurs']) && is_numeric($_POST['nbOrdinateurs']) ? (int)$_POST['nbOrdinateurs'] : 0;
            $logiciels = isset($_POST['logiciels']) && is_array($_POST['logiciels']) ? $_POST['logiciels'] : [];
            $imprimante = isset($_POST['imprimante']) ? 1 : 0;
            $typeOrdinateur = htmlspecialchars($_POST['typeOrdinateur']);
            $ecranXXL = isset($_POST['ecranXXL']) ? 1 : 0;

            // Ajout du groupe d'ordinateurs
            $idGroupeOrdinateur = $this->ordinateurModel->ajouterGroupeOrdinateur($nbOrdinateurs, $imprimante, $typeOrdinateur);

            // Ajout des logiciels si présents
            foreach ($logiciels as $logiciel) {
                if (!empty($logiciel) && $logiciel != -1) {
                    $this->ordinateurModel->ajouterLogiciel($idGroupeOrdinateur, htmlspecialchars($logiciel));
                }
            }

            // Ajout de la salle
            try {
                $this->salleModel->ajouterSalle($nom, $capacite, $videoProjecteur, $ecranXXL, $idGroupeOrdinateur);
            } catch (FieldValidationException $e) {
                $this->erreurs = $e->getErreurs();
            }
        }
    }

    /**
     * Gère la modification d'une salle
     * @param int $salleId
     */
    public function modifierSalle($salleId)
    {
        $salle = $this->salleModel->getSalle($salleId);

        if (isset($_POST['supprimerLogiciel'])) {
            $logicielId = htmlspecialchars($_POST['logicielId']);
            $nbReservations = $this->ordinateurModel->supprimerLogiciel($salle['ID_ORDINATEUR'], $logicielId);
        }

        try {
            $ordinateurs = $this->ordinateurModel->getOrdinateursSalle($salleId);
            $logicielsInstalles = $this->ordinateurModel->getLogicielsOrdinateur($salle['ID_ORDINATEUR']);
            $logiciels = $this->ordinateurModel->getLogiciels();
            $typesOrdinateur = $this->ordinateurModel->getTypesOrdinateur();
        } catch (\Exception $e) {
            $ordinateurs = null;
            $logiciels = null;
            $logicielsInstalles = null;
            $typesOrdinateur = null;
        }

        if (isset($_POST['nom'])
            && isset($_POST['capacite'])) {

            // Récupérer les données du formulaire pour la salle
            $nom = htmlspecialchars($_POST['nom']);
            $capacite = (int)htmlspecialchars($_POST['capacite']);
            $videoProjecteur = isset($_POST['videoProjecteur']) ? 1 : 0;
            $ecranXXL = isset($_POST['ecranXXL']) ? 1 : 0;

            // Récupérer les données du formulaire pour les ordinateurs
            $nbOrdinateurs = isset($_POST['nbOrdinateurs']) && is_numeric($_POST['nbOrdinateurs']) ? (int)$_POST['nbOrdinateurs'] : 0;
            $imprimante = isset($_POST['imprimante']) ? 1 : 0;
            $typeOrdinateur = htmlspecialchars($_POST['typeOrdinateur']);
            $logicielsSelectionnes = isset($_POST['logiciels']) ? $_POST['logiciels'] : [];

            try {
                // Modifier les informations de la salle
                $this->salleModel->modifierSalle($salleId, $nom, $capacite, $videoProjecteur, $ecranXXL);

                // Modifier les informations du groupe d'ordinateurs
                $this->ordinateurModel->modifierGroupeOrdinateur($salle['ID_ORDINATEUR'], $nbOrdinateurs, $imprimante, $typeOrdinateur);

            } catch (\Exception $e) {
                $this->erreurs = $e->getMessage();
            }
        }

        require __DIR__ . '/../views/modifierSalle.php';
    }

    /**
     * Fonction qui gère la suppression d'une salle
     * @param $salleId int Identifiant de la salle
     */
    public function supprimerSalle($salleId)
    {
        if (isset($_POST['supprimerSalle'])) {
            $nbReservations = $this->reservationModel->getNbReservationsSalle($salleId);
            $this->salleModel->supprimerSalle($salleId, $nbReservations);

            header('Location: /salles');
        }
    }
}
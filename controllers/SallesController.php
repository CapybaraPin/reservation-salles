<?php

namespace controllers;

use PDO;
use services\Config;
use services\exceptions\FieldValidationException;

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
        'nom' => ['label' => 'Salle', 'type' => PDO::PARAM_STR, 'champ' => 'salle.nom'],
        'capacite' => ['label' => 'Capacité', 'type' => PDO::PARAM_INT, 'operateur' => '>=', 'champ' => 'salle.capacite'],
        'videoProjecteur' => ['label' => 'Vidéo Projecteur', 'type' => PDO::PARAM_INT, 'operateur' => '=', 'champ' => 'salle.videoProjecteur'],
        'ecranXXL' => ['label' => 'Écran XXL', 'type' => PDO::PARAM_INT, 'operateur' => '=', 'champ' => 'salle.ecranXXL'],
    ];

    /**
     * Fonction pour gérer les requêtes GET
     */
    public function get() {

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
                'attributs' => ['class' => 'btn btn-nav', 'title' => 'SupprimerSalle', 'href' => '#'.$salle['ID']],
                'icone' => 'fa-solid fa-trash-can'
            ];

        }

        $typesOrdinateur = $this->ordinateurModel->getTypesOrdinateur();
        $logiciels = $this->ordinateurModel->getLogiciels();

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

        require __DIR__ . '/../views/salles.php';

    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post() {

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

        if (isset($_POST['supprimerSalle']) && isset($_POST['idSalle'])) {
            try {
                $id = intval($_POST['idSalle']);
                $this->supprimerSalle($id);
            } catch (\Exception $e) {
                $this->erreurs = "Erreur lors de la suppression de la salle : " . $e->getMessage();
            }
        }

        $this->get();

    }

    /**
     * Fonction qui gère l'ajout d'une salle
     */
    public function ajouterSalle() {
        // Récupération des données du formulaire
        if (isset($_POST['nom']) && isset($_POST['capacite']) && isset($_POST['typeOrdinateur'])) {

            $nom = htmlspecialchars($_POST['nom'], ENT_NOQUOTES);
            $capacite = (int)htmlspecialchars($_POST['capacite']);
            $videoProjecteur = isset($_POST['videoProjecteur']) ? 1 : 0;
            $nbOrdinateurs = isset($_POST['nbOrdinateurs']) && is_numeric($_POST['nbOrdinateurs']) ? (int)$_POST['nbOrdinateurs'] : 0;
            $logiciels = isset($_POST['logiciels']) && is_array($_POST['logiciels']) ? $_POST['logiciels'] : [];
            $imprimante = isset($_POST['imprimante']) ? 1 : 0;
            $typeOrdinateur = htmlspecialchars($_POST['typeOrdinateur']);
            $ecranXXL = isset($_POST['ecranXXL']) ? 1 : 0;

            // Ajout du groupe d'ordinateurs
            $idGroupeOrdinateur = $this->ordinateurModel->ajouterGroupeOrdinateur($nbOrdinateurs, $imprimante, $typeOrdinateur);

            if (!is_null($idGroupeOrdinateur)) {
                // Ajout des logiciels si présents
                foreach ($logiciels as $logiciel) {
                    if (!empty($logiciel) && $logiciel != -1) {
                        $this->ordinateurModel->ajouterLogiciel($idGroupeOrdinateur, $logiciel);
                    }
                }
            }

            try {
                $this->salleModel->ajouterSalle($nom, $capacite, $videoProjecteur, $ecranXXL, $idGroupeOrdinateur);
                $this->success = "La salle <b>« $nom »</b> a bien été ajoutée.";
            } catch (FieldValidationException $e) {
                $this->erreurs = $e->getErreurs();
            }

        }
    }



    /**
     * Fonction qui gère la suppression d'une salle
     * @param $salleId int Identifiant de la salle
     */
    public function supprimerSalle($salleId) {
        if (isset($_POST['supprimerSalle'])) {
            $nbReservations = $this->reservationModel->getNbReservationsSalle($salleId);
            if($nbReservations == 0) {

                try {
                    $this->salleModel->supprimerSalle($salleId);
                    $_SESSION['messageValidation'] =  "La salle n°".$salleId." a bien été supprimée.";
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit;
                } catch (\Exception $e) {
                    $_SESSION['messageErreur'] =  "La salle n°".$salleId." n'existe plus.";

                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit;
                }

            } else {
                throw new \Exception("Impossible de supprimer une salle avec des réservations.");
            }
        }
    }

}

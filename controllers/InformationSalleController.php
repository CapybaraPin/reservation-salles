<?php

namespace controllers;

/**
 * Contrôleur pour la page des information et modification d'une salle
 */
class InformationSalleController extends FiltresController {

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
    public function get($salleID = null, $action = null) {

        switch ($action) {
            case 'view':
                $this->consultationSalle($salleID);
                break;
            case 'edit':
                $this->ajouterLogiciel($salleID);
                $this->modifierSalle($salleID);
                break;
        }
    }

    /**
     * Fonction pour gérer les requêtes POST
     */
    public function post($salleID = null, $action = null) {

        $this->get($salleID, $action);
    }

    /**
     * Fonction qui gère la consultation d'une salle
     * @param $salleId int Identifiant de la salle
     * @return void Affiche la page de consultation d'une salle
     */
    public function consultationSalle($salleId) {

        $salle = $this->salleModel->getSalle($salleId);
        $this->supprimerSalle($salleId);

        try {
            $ordinateurs = $this->ordinateurModel->getOrdinateursSalle($salleId);
            $logiciels = $this->ordinateurModel->getLogicielsOrdinateur($salle['ID_ORDINATEUR']);
            $nbReservations = $this->reservationModel->getNbReservationsSalle($salleId);
        } catch (\Exception $e) {
            $groupeOrdinateur = null;
            $logiciels = null;
        }

        if(isset($_SESSION['messageValidation'])) {
            $this->success = $_SESSION['messageValidation'];
            unset($_SESSION['messageValidation']);
        }

        $erreurs = $this->erreurs;
        $success = $this->success;

        require __DIR__ . '/../views/consultationSalle.php';
    }

    /**
     * Gère la modification d'une salle
     * @param int $salleId
     */
    public function modifierSalle($salleId) {

        $salle = $this->salleModel->getSalle($salleId);
        $this->ajouterLogiciel($salleId);

        if (isset($_POST['supprimerLogiciel'])) {
            $logicielId = htmlspecialchars($_POST['logicielId']);
            $nbReservations = $this->ordinateurModel->supprimerLogiciel($salle['ID_ORDINATEUR'], $logicielId);

            $this->erreurs = "Le logiciel a bien été supprimé ce cette salle.";
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

                $success = "Modification de la salle avec succès.";

                $salle = $this->salleModel->getSalle($salleId);
                $ordinateurs = $this->ordinateurModel->getOrdinateursSalle($salleId);
                $logicielsInstalles = $this->ordinateurModel->getLogicielsOrdinateur($salle['ID_ORDINATEUR']);
                $logiciels = $this->ordinateurModel->getLogiciels();
                $typesOrdinateur = $this->ordinateurModel->getTypesOrdinateur();
            } catch (\Exception $e) {
                $this->erreurs = $e->getMessage();
            }
        }

        if(isset($_SESSION['messageValidation'])) {
            $this->success = $_SESSION['messageValidation'];
            unset($_SESSION['messageValidation']);
        }

        $erreurs = $this->erreurs;
        $success = $this->success;

        require __DIR__ . '/../views/modifierSalle.php';
    }

    /**
     * Fonction qui gère la suppression d'une salle
     * @param $salleId int Identifiant de la salle à supprimer
     */
    public function supprimerSalle($salleId) {
        if (isset($_POST['supprimerSalle'])) {
            $nbReservations = $this->reservationModel->getNbReservationsSalle($salleId);
            if($nbReservations == 0) {
                $result = $this->salleModel->supprimerSalle($salleId);

                if($result) {
                    $_SESSION['messageValidation'] =  "La salle n°".$salleId." a bien été supprimée.";

                    header("Location: /salles");
                    exit;
                } else {
                    throw new \Exception("Une erreur est survenue");
                }

            } else {
                throw new \Exception("Impossible de supprimer une salle avec des réservations. ". $salleId . " - " . $nbReservations);
            }
        }
    }

    /**
     * Fonction qui gère l'ajout d'un logiciel à une salle
     * @param $salleId int identifiant de la salle
     */
    public function ajouterLogiciel($salleId) {
        if (isset($_POST["ajouterLogiciel"]) && isset($_POST["logicielId"])){
            $salle = $this->salleModel->getSalle($salleId);
            $logicielId = htmlspecialchars($_POST["logicielId"]);

            $this->ordinateurModel->ajouterLogiciel($salle["ID_ORDINATEUR"], $logicielId);

            $_SESSION['messageValidation'] = "Vous avez bien ajouté le logiciel à la salle.";

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
    }


}
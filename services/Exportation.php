<?php

namespace services;

use DateInterval;

/**
 * Class Exportation
 *
 * Cette classe permet de gérer l'exportation des données de l'application.
 *
 * @package services
 */
class Exportation
{

    /**
     * Entête du fichier d'exportation des réservations
     */
    const ENTETE_RESERVATION = [
        'Ident',
        'salle',
        'employe',
        'activite',
        'date',
        'heuredebut',
        'heurefin',
        '', '', '', '', ''
    ];

    /**
     * Entête du fichier d'exportation des salles
     */
    const ENTETE_SALLE = [
        'Ident',
        'Nom',
        'Capacite',
        'videoproj',
        'ecranXXL',
        'ordinateur',
        'type',
        'logiciels',
        'imprimante'
    ];

    /**
     * Entete du fichier d'exportation des employes
     */
    const ENTETE_EMPLOYE = [
        'Ident',
        'Nom',
        'Prenom',
        'Telephone',
    ];

    /**
     * Permet de récupérer la liste des réservations dans la base de données. Formatées pour l'exportation
     * Contient l'entête du fichier
     *
     * @return array, Retourne la liste des réservations obtenue
     */
    public function getReservations()
    {
        $reservation = new Reservation();
        $reservations = $reservation->getReservationsExport();

        $reservationsExport[] = self::ENTETE_RESERVATION;
        foreach ($reservations as $reservation) {
            $activite = $reservation['TYPE_ACTIVITE'];
            $ligne = $this->genererLignesCommuneRes($reservation);

            switch ($activite) {
                case ($activite == 'prêt' || $activite == 'location'):
                    $organisme = new Organisme();
                    $interlocuteur = $organisme->getInterlocuteur($reservation['IDENTIFIANT_ORGANISME']);
                    $ligne[7] = $reservation['NOM_ORGANISME'];
                    $ligne[8] = $interlocuteur['NOM_INTERLOCUTEUR'];
                    $ligne[9] = $interlocuteur['PRENOM_INTERLOCUTEUR'];
                    $ligne[10] = $interlocuteur['TELEPHONE_INTERLOCUTEUR'];
                    $ligne[11] = $reservation['DESCRIPTION'];
                    break;
                case 'formation':
                    $ligne[7] = $reservation['DESCRIPTION'];
                    $ligne[8] = $reservation['NOM_FORMATEUR'];
                    $ligne[9] = $reservation['PRENOM_FORMATEUR'];
                    $ligne[10] = $reservation['TELEPHONE_FORMATEUR'];
                    break;
                default:
                    $ligne[7] = $reservation['DESCRIPTION'];
                    break;
            }
            $reservationsExport[] = $ligne;
        }

        return $reservationsExport;
    }

    /**
     * Permet de récupérer la liste des salles dans la base de données. Formatées pour l'exportation
     * Contient l'entête du fichier
     *
     * @return array, Retourne la liste des salles obtenue
     */
    public function getSalles()
    {
        $salle = new Salle();
        $salles = $salle->getSalles(0, [], $salle->getNbSalles());

        $sallesExport[] = self::ENTETE_SALLE;
        foreach ($salles as $salle) {
            $ligne = $this->genererTableauVide(count(self::ENTETE_SALLE));
            $ligne[0] = $this->genererIdentifiant($salle['ID_SALLE'], 8);
            $ligne[1] = $salle['NOM_SALLE'];
            $ligne[2] = $salle['CAPACITE'];
            $ligne[3] = $salle['VIDEO_PROJECTEUR'] ? 'Oui' : 'Non';
            $ligne[4] = $salle['ECRAN_XXL'] ? 'Oui' : 'Non';
            $ordinateur = new Ordinateur();
            if (!is_null($salle['ID_ORDINATEUR'])) {
                $infoOrdinateur = $ordinateur->getOrdinateur($salle['ID_ORDINATEUR']);
                $ligne[5] = $infoOrdinateur['nbOrdinateur'];
                $ligne[6] = $infoOrdinateur['DesignationType'];
                $logiciels = $ordinateur->getLogiciels($salle['ID_ORDINATEUR']);
                $ligne[7] = implode(', ', array_column($logiciels, 'nom'));
                $ligne[8] = $infoOrdinateur['imprimante'] ? 'Oui' : 'Non';
            }

            $sallesExport[] = $ligne;
        }

        return $sallesExport;
    }

    /**
     * Permet de récupérer la liste des employés dans la base de données. Formatées pour l'exportation
     * Contient l'entête du fichier
     * @return array, Retourne la liste des employés obtenue
     */
    public function getEmployes()
    {
        $employe = new Employe();
        $employes = $employe->getEmployes(0, [], $employe->getNbEmployes());

        $employesExport[] = self::ENTETE_EMPLOYE;
        foreach ($employes as $employe) {
            $ligne = $this->genererTableauVide(count(self::ENTETE_EMPLOYE));
            $ligne[0] = "E" . $this->genererIdentifiant($employe['IDENTIFIANT_EMPLOYE'], 6);
            $ligne[1] = $employe['NOM_EMPLOYE'];
            $ligne[2] = $employe['PRENOM_EMPLOYE'];
            $ligne[3] = $employe['TELEPHONE_EMPLOYE'];

            $employesExport[] = $ligne;
        }

        return $employesExport;
    }

    /**
     * Génère chaque ligne d'une réservation donnée
     * exemple : si la période tient sur 3 jours, 3 lignes seront générées
     * @param array $reservation La réservation à traiter
     */
    private function genererLignesCommuneRes(array $reservation) {
        $dateDebut = new \DateTime($reservation['DATE_DEBUT']);
        $dateFin = new \DateTime($reservation['DATE_FIN']);

        // Générer les lignes
        $ligne = $this->genererTableauVide(count(self::ENTETE_RESERVATION));

        // Données communes
        $idFormat = "R" . $this->genererIdentifiant($reservation['IDENTIFIANT_RESERVATION'], 6);
        $salleFormat = $this->genererIdentifiant($reservation['IDENTIFIANT_SALLE'], 8);
        $employeFormat = "E" . $this->genererIdentifiant($reservation['IDENTIFIANT_EMPLOYE'], 6);

        $ligne[0] = $idFormat;
        $ligne[1] = $salleFormat;
        $ligne[2] = $employeFormat;
        $ligne[3] = $reservation['TYPE_ACTIVITE'];
        $ligne[4] = $dateDebut->format('d/m/Y');
        // 15h30 format
        $ligne[5] = $dateDebut->format('H\hi');
        $ligne[6] = $dateFin->format('H\hi');

        return $ligne;
    }

    /**
     * Génère tableau vide de la taille donnée,
     * Permet d'avoir les bonnes dimensions pour l'exportation
     * @param $nbLignes int le nombre de lignes
     * @return array, Retourne un tableau vide de la taille donnée
     */
    public static function genererTableauVide($nbLignes) {
        $tableau = [];
        for ($i = 0; $i < $nbLignes; $i++) {
            $tableau[] = '';
        }
        return $tableau;
    }

    /**
     * Génère un identifiant en fonction de l'id et de la taille souhaitée
     * @param $id int l'identifiant
     * @param $taille int la taille souhaitée
     */
    public static function genererIdentifiant($id, $taille)
    {
        return str_pad($id, $taille, "0", STR_PAD_LEFT);
    }
}
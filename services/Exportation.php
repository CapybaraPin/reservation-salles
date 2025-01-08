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
     * Permet de récupérer la liste des réservations dans la base de données. Formatées pour l'exportation
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
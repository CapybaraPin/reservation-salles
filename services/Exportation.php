<?php

namespace services;



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
     * Permet de récupérer la liste des réservations dans la base de données. Formatées pour l'exportation
     *
     * @return array, Retourne la liste des réservations obtenue
     */
    function getReservation()
    {
        $reservation = new Reservation();
        $reservations = $reservation->getReservations();

        $reservationsExport = [];

    }
}
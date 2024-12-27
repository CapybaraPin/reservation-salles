<?php

namespace controllers;

/**
 * Contrôleur de base pour la gestion des filtres.
 */
class FiltresController extends Controller
{
    private $filtresDisponibles;

    private $filtres = [];

    /**
     * Récupère les filtres actuels.
     */
    public function getFiltres()
    {
        return $this->filtres;
    }

    /**
     * Récupère les filtres pour la requête.
     */
    public function getFiltresRequete()
    {
        $filtres = $this->getFiltres();
        $filtre = [];
        foreach ($filtres as $champ => $valeurs) {
            foreach ($valeurs as $valeur) {
                $filtre[$champ][] = ['valeur' => $valeur, "type" => $this->filtresDisponibles[$champ]['type'],
                                     'operateur' => $this->filtresDisponibles[$champ]['operateur'] ?? null];
            }
        }
        return $filtre;
    }


    /**
     * Affecte les filtres disponibles.
     */
    public function setFiltresDisponibles($filtresDisponibles)
    {
        $this->filtresDisponibles = $filtresDisponibles;
    }

    /**
     * Affecte les filtres actuels.
     */
    public function setFiltres($filtres)
    {
        $this->filtres = $filtres;
    }

    /**
     * Ajoute un filtre à la liste.
     * @param array $nouveauFiltre
     */
    public function ajouterFiltre(array $nouveauFiltre)
    {
        $champ = $nouveauFiltre['champ'] ?? null;
        $valeur = $nouveauFiltre['valeur'] ?? null;

        if ($champ && $valeur && isset($this->filtresDisponibles[$champ])) {
            if (!isset($this->filtres[$champ])) {
                $this->filtres[$champ] = [];
            }
            for ($indice = 0;
                 $indice < sizeof($this->filtres[$champ]) && $this->filtres[$champ][$indice] != null;
                 $indice++);

            $this->filtres[$champ][$indice] = $valeur;
        }
    }

    /**
     * Supprime un filtre existant.
     */
    public function supprimerFiltre(array $supprimerFiltre)
    {
        $champ = array_key_first($supprimerFiltre);
        $indice = array_key_first($supprimerFiltre[$champ] ?? []);

        if (isset($this->filtres[$champ][$indice])) {
            unset($this->filtres[$champ][$indice]);
        }
    }
}
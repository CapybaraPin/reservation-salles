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
                                     'operateur' => $this->filtresDisponibles[$champ]['operateur'] ?? null, 'champ' => $this->filtresDisponibles[$champ]['champ']];
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
     * si le filtre contient un json, on le décode
     */
    public function setFiltres($filtres)
    {
        foreach ($filtres as $champ => $valeurs) {
            foreach ($valeurs as $index => $valeur) {
                $valeurDecode = json_decode($valeur, true);
                $this->filtres[$champ][$index] = is_array($valeurDecode) ? $valeurDecode : $valeur;
            }
        }
    }

    /**
     * Ajoute un filtre à la liste.
     * @param array $nouveauFiltre
     */
    public function ajouterFiltre(array $nouveauFiltre)
    {
        $champ = $nouveauFiltre['champ'] ?? null;
        if ($champ == "date" && isset($nouveauFiltre['date'])) {
            $valeur = date('Y-m-d', strtotime($nouveauFiltre['date']));
        } elseif ($champ == "periode" && isset($nouveauFiltre['dateDebut']) && isset($nouveauFiltre['dateFin']))  {
            $datedebut = date_create($nouveauFiltre['dateDebut']);
            $datefin = date_create($nouveauFiltre['dateFin']);
            if (date_diff($datedebut, $datefin)->format('%R') == '-') {
                $valeur = [$datefin->format('Y-m-d H:i:s'), $datedebut->format('Y-m-d H:i:s')];
            } else {
                $valeur = [$datedebut->format('Y-m-d H:i:s'), $datefin->format('Y-m-d H:i:s')];
            }
        } else {
            $valeur = $nouveauFiltre['valeur'] ?? null;
        }

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
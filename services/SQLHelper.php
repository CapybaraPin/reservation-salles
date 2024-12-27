<?php

namespace services;

use PDOStatement;

class SQLHelper
{

    /**
     * Construit les conditions de filtres pour une requête SQL
     * @param array $filtre Filtres de recherche
     * @return string Conditions de filtres pour une requête SQL
     */
    static function construireConditionsFiltres($filtre = []) {
        $sql = "";

        // Ensure $filtre is an array
        if (!is_array($filtre)) {
            return $sql; // Return la requête de base si ce n'est pas un array
        }

        // Vérifier si le filtre est vide
        if (empty($filtre)) {
            return $sql; // Retourne seulement la requête de base
        }

        $conditions = [];

        foreach ($filtre as $champ => $valeurs) {
            if (!is_array($valeurs)) {
                continue; // saute si ce n'est pas un array
            }

            $sousConditions = [];

            foreach ($valeurs as $index => $filtreDetail) {
                // Vérifier que la valeur est définie et non vide
                if (empty($filtreDetail['valeur'])) {
                    continue;
                }

                $operateur = $filtreDetail['operateur'] ?? 'LIKE';
                $parametre = str_replace('.', '_', $champ) . "_$index"; // Nom unique pour chaque paramètre
                $sousConditions[] = "$champ $operateur :$parametre";
            }

            if (!empty($sousConditions)) {
                // Ajouter les sous-conditions pour ce champ avec OR
                $conditions[] = '(' . implode(' OR ', $sousConditions) . ')';
            }
        }

        // Ajouter les conditions à la requête SQL si elles existent
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        return $sql;
    }

    /**
     * Lie les valeurs des filtres à une requête préparée
     * @param PDOStatement $req Requête préparée
     * @param array $filtre Filtres de recherche
     */
    static function bindValues($req, $filtre) {
        // S'assure que $filtre est un array
        if (!is_array($filtre)) {
            return; // Ne fait rien si $filtre n'est pas un array
        }

        // Liaison des paramètres avec leurs valeurs et types
        foreach ($filtre as $key => $values) {
            if (!is_array($values)) {
                continue;
            }

            foreach ($values as $index => $filtreDetail) {
                if (!empty($filtreDetail['valeur'])) { // Vérification de la valeur
                    if (is_null($filtreDetail['operateur'])) { // Si l'opérateur n'est pas défini, on ajoute des % pour la recherche LIKE
                        $filtreDetail['valeur'] = '%' . $filtreDetail['valeur'] . '%';
                    }
                    $paramName = str_replace('.', '_', $key) . "_$index";
                    $req->bindValue(":$paramName", $filtreDetail['valeur'], $filtreDetail['type']); // Liaison de la valeur avec le type
                }
            }
        }
    }
}
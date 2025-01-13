<?php

/**
 * Génère un tableau HTML à partir de données
 * @param array $donnees Données à afficher
 * @param array $colonnes Colonnes du tableau
 * @param string $titre Titre du tableau
 * @param int $nbElements Nombre d'éléments
 * @param array $actions Actions à afficher
 * @param int $page Page courante
 * @param int $pageMax Nombre de pages
 * @param array $filtres Filtres de recherche à passer dans les formulaires de pagination
 * @return string HTML du tableau
 */
function genererTableau($donnees, $colonnes, $titre, $nbElements, $actions = [], $page = null, $pageMax = null, $filtres = [])
{
    // Début du tableau HTML
    $html = '<div class="row mb-5">
               <div class="col-12">
                   <div class="border border-1 rounded rounded-4 shadow-sm">
                       <p class="p-3 pb-0 fw-bold">' . htmlspecialchars($titre) . '
                           <button class="btn disabled badge text-primary text-wrap">' .
        ($nbElements === 0 ? "Aucun élément" : "$nbElements éléments") . '
                           </button>
                       </p>
                       <div class="table-responsive">
                       <table class="table">';

    // Entête du tableau
    $html .= genererEntete($colonnes, $actions);
    $html .= '<tbody>';

    if($nbElements > 0) {
        // Génération des lignes du tableau
        foreach ($donnees as $ligne) {
            $html .= genererLigne($ligne, $colonnes, $actions);
        }
    } else {
        $formatTitre = strtolower(str_replace("Mes", "", $titre));
        $html .= '<tr>
                    <td class="text-center" colspan="'.(count($colonnes)+1).'">Aucun(e) '.$formatTitre .' trouvé(e)</td>';
    }

    // Fin du tableau
    $html .= '</tbody>
           </table>
       </div>';

    // Ajout de la pagination si applicable
    if ($page !== null && $pageMax !== null) {
        $html .= genererPagination($page, $pageMax, $filtres);
    }

    $html .= '</div>
           </div>
       </div>';

    return $html;
}

/**
 * Génère l'entête du tableau
 * @param array $colonnes Colonnes du tableau
 * @param array $actions Actions à afficher
 * @return string HTML de l'entête
 */
function genererEntete($colonnes, $actions)
{
    $html = '<thead class="table-light">
                    <tr>';
    foreach ($colonnes as $colonne) {
        $html .= '<th class="centrer">' . htmlspecialchars($colonne) . '</th>';
    }
    if (!empty($actions)) {
        $html .= '<th>Action</th>';
    }
    $html .= '</tr>
                </thead>';
    return $html;
}

/**
 * Génère une ligne du tableau
 * @param array $ligne Ligne du tableau
 * @param array $colonnes Colonnes du tableau
 * @param array $actions Actions à afficher
 * @return string HTML de la ligne
 */
function genererLigne($ligne, $colonnes, $actions)
{
    $html = '<tr>';
    foreach ($colonnes as $key => $colonne) {
        $html .= '<td class="centrer">' . ($ligne[$key] ?? '') . '</td>';
    }

    if (!empty($actions)) {
        $html .= '<td>';
        $actionsCourrante = $actions[$ligne['ID']] ?? [];
        foreach ($actionsCourrante as $action) {
            $html .= '<a ';
            foreach ($action['attributs'] as $attribut => $valeur) {
                $html .= $attribut . '="' . $valeur . '" ';
            }
            $html .= '>' . '<i class="fa-solid ' . $action['icone'] . '"></i>' . '</a>';
        }
        $html .= '</td>';
    }

    $html .= '</tr>';
    return $html;
}


/**
 * Génère la pagination avec les boutons "Précédent" et "Suivant"
 * et les numéros de page et permet la transmition des filtres de recherche
 * de manière POST page par page
 * @param int $page actuelle
 * @param int $pageMax nombre de pages
 * @param array $filtres Filtres de recherche
 * @return string HTML de la pagination
 */
function genererPagination($page, $pageMax, $filtres = [])
{
    // Précédent mobile et desktop
    $html = '
<div class="container-fluid">
    <div class="row">
        <!-- Formulaire pour "Précédent" -->
        <form method="POST" action="?page=' . ($page - 1) . '" class="col-3">';

    // Ajout des filtres sous forme d'inputs cachés
    foreach ($filtres as $champ => $filtresParChamp) {
        foreach ($filtresParChamp as $indice => $valeur) {
            $html .= genererChampsCaches($champ, $valeur, $indice);
        }
    }

    $html .= '
            <button type="submit" class="btn btn-outline-dark d-lg-none">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <button type="submit" class="btn btn-outline-dark d-lg-block d-none">
                <i class="fa-solid fa-arrow-left"></i> Précédent
            </button>
        </form>
        
        <!-- Pages centrales -->
        <div class="col-6 d-flex justify-content-center">
            <nav class="text-center">
                <ul class="pagination-page">
    ';

    // Génération des numéros de page
    for ($i = 1; $i <= $pageMax; $i++) {
        $active = $i == $page ? 'active' : '';
        $html .= '
                    <li class="pagination-item ' . $active . '">
                        <form method="POST" action="?page=' . $i . '">';

        // Ajout des filtres pour chaque formulaire de numéro de page
        foreach ($filtres as $champ => $filtresParChamp) {
            foreach ($filtresParChamp as $indice => $filtre) {
                $html .= genererChampsCaches($champ, $filtre, $indice);
            }
        }

        $html .= '
                            <button type="submit" class="page-link">' . $i . '</button>
                        </form>
                    </li>
        ';
    }

    // Formulaire pour "Suivant"
    $html .= '
                </ul>
            </nav>
        </div>
        <div class="col-3 text-end">
        <form class="d-inline-block" method="POST" action="?page=' . ($page + 1) . '" >';

    // Ajout des filtres dans le formulaire "Suivant"
    foreach ($filtres as $champ => $filtresParChamp) {
        foreach ($filtresParChamp as $indice => $filtre) {
            $html .= genererChampsCaches($champ, $filtre, $indice);
        }
    }

    $html .= '
            <button type="submit" class="btn btn-outline-dark d-lg-none">
                <i class="fa-solid fa-arrow-right"></i>
            </button>
            <button type="submit" class="btn btn-outline-dark d-lg-block d-none">
                Suivant <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
        </div>
    </div>
</div>';

    return $html;
}

/**
* Génère des champs cachés pour les filtres
* @param string $champ Nom du champ
* @param array|string $filtresParChamp Valeurs des filtres
* @param int $indice Indice du filtre
* @return string HTML des champs cachés
*/
function genererChampsCaches($champ, $filtresParChamp, $indice)
{
    $html = '';

    if (is_array($filtresParChamp)) {
        $html .= '<input type="hidden" name="filtres[' . htmlspecialchars($champ) . '][' . htmlspecialchars($indice) . ']" value="' . htmlspecialchars(json_encode($filtresParChamp)) . '">';
    } else {
        $html .= '<input type="hidden" name="filtres[' . htmlspecialchars($champ) . '][' . htmlspecialchars($indice) . ']" value="' . htmlspecialchars($filtresParChamp) . '">';
    }
    return $html;
}
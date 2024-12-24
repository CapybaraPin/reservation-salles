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
 * @return string HTML du tableau
 */
function genererTableau($donnees, $colonnes, $titre, $nbElements, $actions = [], $page = null, $pageMax = null)
{
    // Début du tableau HTML
    $html = '<div class="row">
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

    // Génération des lignes du tableau
    foreach ($donnees as $ligne) {
        $html .= genererLigne($ligne, $colonnes, $actions);
    }

    // Fin du tableau
    $html .= '</tbody>
           </table>
       </div>';

    // Ajout de la pagination si applicable
    if ($page !== null && $pageMax !== null) {
        $html .= genererPagination($page, $pageMax);
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
                    <tr>
                        <th><input type="checkbox" class="ms-2 form-check-input"></th>';
    foreach ($colonnes as $colonne) {
        $html .= '<th>' . htmlspecialchars($colonne) . '</th>';
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
    $html = '<tr>
                <td><input type="checkbox" class="ms-2 form-check-input"></td>';
    foreach ($colonnes as $key => $colonne) {
        $html .= '<td>' . htmlspecialchars($ligne[$key] ?? '') . '</td>';
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
 * et les numéros de page
 * @param int $page actuelle
 * @param int $pageMax nombre de pages
 * @return string HTML de la pagination
 */
function genererPagination(int $page, int $pageMax)
{
// Précédent mobile et desktop
    $html = '
<div class="container-fluid">
    <div class="row">
        <!-- Bouton "Précédent" pour mobile -->
        <div class="col-3 d-lg-none d-block">
            <a href="?page=' . ($page - 1) . '" class="btn btn-outline-dark d-lg-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
        <!-- Bouton "Précédent" pour desktop -->
        <div class="col-3 d-lg-block d-none">
            <a href="?page=' . ($page - 1) . '" class="btn btn-outline-dark">
                <i class="fa-solid fa-arrow-left"></i> Précédent
            </a>
        </div>
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
                        <a class="page-link" href="?page=' . $i . '">' . $i . '</a>
                    </li>
    ';
    }

// Suivant mobile et desktop
    $html .= '
                </ul>
            </nav>
        </div>
        <!-- Bouton "Suivant" pour desktop -->
        <div class="col-3 text-end d-lg-block d-none">
            <a href="?page=' . $page + 1 . '" class="btn btn-outline-dark">Suivant
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        <!-- Bouton "Suivant" pour mobile -->
        <div class="col-3 text-end d-lg-none d-block">
            <a href="?page=' . $page + 1 . '" class="btn btn-outline-dark d-lg-none">
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
';

    return $html;
}
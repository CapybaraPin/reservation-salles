<?php

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
            <a href="?page=' . $page - 1 . '" class="btn btn-outline-dark d-lg-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
        <!-- Bouton "Précédent" pour desktop -->
        <div class="col-3 d-lg-block d-none">
            <a href="?page=' . $page - 1 . '" class="btn btn-outline-dark">
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
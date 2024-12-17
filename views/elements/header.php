<?php

$page_actuelle = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

?>

<!-- Section en-tête -->
<div class="header">
    <div class="row">
        <!-- Navigation principale -->
        <nav class="nav navbar navbar-expand-lg col-12 ms-1">
            <!-- Bouton de burger pour les écrans petits -->
            <button class="d-lg-none d-block navbar-burger m-0" type="button" aria-label="toggle navigation" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Contenu du menu pour les grands écrans -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-lg-0">
                    <li class="btn-nav me-1 button-nav mb-1 mb-lg-0 <?= ($page_actuelle == '/') ? 'btn-nav-active' : ''; ?>">
                        <a href="/">Tableau de bord</a>
                    </li>
                    <li class="btn-nav me-1 button-nav mb-1 mb-lg-0 <?= ($page_actuelle == '/reservations') ? 'btn-nav-active' : ''; ?>">
                        <a href="/reservations">Réservations</a>
                    </li>
                    <li class="btn-nav me-1 button-nav mb-1 mb-lg-0 <?= ($page_actuelle == '/salles') ? 'btn-nav-active' : ''; ?>">
                        <a href="/salles">Salles</a>
                    </li>
                    <li class="btn-nav me-1 button-nav mb-1 mb-lg-0 <?= ($page_actuelle == '/employes') ? 'btn-nav-active' : ''; ?>">
                        <a href="/employes">Employés</a>
                    </li>
                    <li class="btn-nav me-1 button-nav mb-1 mb-lg-0 <?= ($page_actuelle == '/activites') ? 'btn-nav-active' : ''; ?>">
                        <a href="/activites">Activités</a>
                    </li>
                </ul>
                <!-- Bouton de déconnexion -->
                <form method="post" action="">
                    <button name="deconnexion" class="btn btn-outline-dark me-1 me-4 ms-1 mb-1 mb-lg-0">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion
                    </button>
                </form>
            </div>
        </nav>
    </div>
</div>

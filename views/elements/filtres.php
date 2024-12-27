<div class="row mb-4 mt-3">
    <div class="col-12 col-lg-9">

        <form method="post" class="d-inline-block">
            <?php
            // Ajouter les filtres existants en tant que champs cachés
            foreach ($filtres as $champ => $filtresParChamp) {
                foreach ($filtresParChamp as $indice => $valeur) { ?>
                    <input type="hidden" name="filtres[<?= htmlspecialchars($champ) ?>][<?= htmlspecialchars($indice) ?>]" value="<?= htmlspecialchars($valeur) ?>">
                    <button type="submit" name="supprimer_filtre[<?= htmlspecialchars($champ) ?>][<?= htmlspecialchars($indice) ?>]" class="btn border border-1 shadow-sm me-2 mb-1">
                        <?php
                        $operateur = isset($filtresDisponibles[$champ]['operateur']) ? $filtresDisponibles[$champ]['operateur'] : "=";

                        echo htmlspecialchars($filtresDisponibles[$champ]['label'] . " " . "$operateur"
                            . " " . "$valeur") ?>
                        <i class="fa-solid fa-xmark text-primary ps-2"></i>
                    </button>
            <?php } } ?>
        </form>
        <form method="post" class="d-inline-block">
            <?php
            // Ajouter les filtres existants en tant que champs cachés
            foreach ($filtres as $champ => $filtresParChamp) {
                foreach ($filtresParChamp as $indice => $filtre) { ?>
                    <input type="hidden" name="filtres[<?= htmlspecialchars($champ) ?>][<?= htmlspecialchars($indice) ?>]" value="<?= htmlspecialchars($filtre) ?>">
                <?php } } ?>
            <!-- Bouton pour afficher plus de filtres -->
            <btn id="btn-filtrer" class="btn border border-1 shadow-sm me-2 mb-1">
                <i class="fa-solid fa-filter"></i> Ajouter un filtre
            </btn>
            <div id="menu-deroulant" class="border border-1 menu-deroulant p-3 shadow-sm">
                <!-- Liste des filtres disponibles -->
                <select name="nouveau_filtre[champ]" class="form-select mb-3">
                    <option value="">Sélectionnez un filtre</option>
                    <?php foreach ($filtresDisponibles as $champ => $details): ?>
                        <option value="<?= htmlspecialchars($champ) ?>"><?= htmlspecialchars($details['label']) ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Valeur à ajouter -->
                <input type="text" name="nouveau_filtre[valeur]" class="form-control mb-3" placeholder="Valeur…">

                <!-- Boutons d'action -->
                <div class="row">
                    <div class="col-6">
                        <span type="button" id="btn-annuler" class="btn btn-outline-dark w-100">Annuler</span>
                    </div>
                    <div class="col-6">
                        <button type="submit" name="ajouter_filtre" class="btn btn-primary w-100">Appliquer</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Champ de recherche -->
    <div class="col-12 col-lg-3 mt-lg-0 mt-2">
        <div class="input-group">
            <i class="fa-solid input-group-text d-flex">&#xf002;</i>
            <input class="form-control" type="text" name="recherche" placeholder="Recherche">
        </div>
    </div>
</div>
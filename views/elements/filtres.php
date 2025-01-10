<div class="row mb-4 mt-3">
    <div class="col-12 col-lg-9">

        <form method="post" class="d-inline-block">
            <?php
            // Ajouter les filtres existants en tant que champs cachés
            foreach ($filtres as $champ => $filtresParChamp) {
                foreach ($filtresParChamp as $indice => $valeur) {
                    echo genererChampsCaches($champ, $valeur, $indice);?>
                    <button type="submit" name="supprimer_filtre[<?= htmlspecialchars($champ) ?>][<?= htmlspecialchars($indice) ?>]" class="btn border border-1 shadow-sm me-2 mb-1">
                        <?php
                        $operateur = isset($filtresDisponibles[$champ]['operateur']) ? $filtresDisponibles[$champ]['operateur'] : "=";
                        $input = isset($filtresDisponibles[$champ]['input']) ? $filtresDisponibles[$champ]['input'] : "text";

                        if ($input === 'date') {
                            echo htmlspecialchars($filtresDisponibles[$champ]['label'] . " $operateur "
                                . date('d/m/Y', strtotime($valeur)));
                        } elseif ($input === 'datetime-local') {
                            echo htmlspecialchars($filtresDisponibles[$champ]['label']
                                . " " . $valeur[0] . " et " . $valeur[1]);
                        } else {
                            echo htmlspecialchars($filtresDisponibles[$champ]['label'] . " $operateur "
                                . $valeur);
                        }

                        ?>
                        <i class="fa-solid fa-xmark text-primary ps-2"></i>
                    </button>
            <?php } } ?>
        </form>
        <form method="post" class="d-inline-block">
            <?php
            // Ajouter les filtres existants en tant que champs cachés
            foreach ($filtres as $champ => $filtresParChamp) {
                foreach ($filtresParChamp as $indice => $filtre) {
                    echo genererChampsCaches($champ, $filtre, $indice);
                }
            }
            ?>
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
                <input type="text" id="inputText" name="nouveau_filtre[valeur]" class="form-control mb-3" placeholder="Valeur…">
                <?php
                // Recherche si un filtre de type date ou datetime-local est présent
                $filtreDatePresent = false;
                $filtrePeriodePresent = false;
                foreach ($filtresDisponibles as $champ => $details) {
                    if (isset($details['input']) && $details['input'] === 'date') {
                        $filtreDatePresent = true;
                    }
                    if (isset($details['input']) && $details['input'] === 'datetime-local') {
                        $filtrePeriodePresent = true;
                    }
                }

                if ($filtreDatePresent) {?>
                <div id="inputDate">
                    <label for="date" class="mb-1">Veuillez sélectionner une date :</label>
                    <input type="date" id="date" name="nouveau_filtre[date]" class="form-control mb-3" placeholder="Valeur">
                </div>
                <?php } ?>
                <?php if ($filtrePeriodePresent) {?>
                <div id="inputDatetime-local">
                    <label for="dateDebut" class="mb-1">Veuillez sélectionner une date entre :</label>
                    <input type="datetime-local" id="dateDebut" name="nouveau_filtre[dateDebut]" class="form-control mb-1" placeholder="Valeur">
                    <label for="dateFin" class="mb-1">Et :</label>
                    <input type="datetime-local" id="dateFin" name="nouveau_filtre[dateFin]" class="form-control mb-3" placeholder="Valeur">
                </div>
                <?php } ?>
                <!-- Boutons d'action -->
                <div class="row">
                    <div class="col-12">
                        <span type="button" id="btn-annuler" class="btn btn-outline-dark">Annuler</span>
                        <button type="submit" name="ajouter_filtre" class="btn btn-primary float-end">Appliquer</button>
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
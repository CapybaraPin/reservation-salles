const navbarToggler = document.querySelector(".navbar-burger");
const contentNavBar = document.querySelector(".collapse");

// Sélectionner le bouton "Suivant" et le conteneur de contenu
const nextButton = document.getElementById('next-button');
const contentSection = document.getElementById('content-section');
const form = document.querySelector('form');  // Sélectionner le formulaire pour pouvoir gérer la soumission

// Variable pour suivre l'étape actuelle
let currentStep = 1;

// Ajouter un écouteur d'événement pour le clic sur "Suivant"
nextButton.addEventListener('click', (event) => {
    event.preventDefault();  // Empêcher le comportement par défaut (soumission immédiate)

    if (currentStep === 1) {
        // Étape 1 : Remplacer avec le contenu de la première étape
        contentSection.innerHTML = `
            <div class="row">
                <!-- Nouvelle liste déroulante -->
                <div class="form-group mt-1 mb-1">
                    <label class="label-form" for="typeReservation">Nouvelle liste déroulante</label>
                    <select class="form-select" id="newSelection" name="newSelection">
                        <option value="0">Sélectionner un type</option>
                        <option value="1">Type 1</option>
                        <option value="2">Type 2</option>
                    </select>
                </div>
            </div>
        `;
        currentStep++; // Passer à l'étape suivante
    } else if (currentStep === 2) {
        // Étape 2 : Remplacer avec le contenu de la deuxième étape
        contentSection.innerHTML = `
            <div class="row">
                <!-- Champ Nom formateur -->
                <div class="form-group mb-1">
                    <label class="label-form" for="nomFormateur">Nom du formateur</label>
                    <input class="form-control" id="nomFormateur" name="nomFormateur" type="text" placeholder="Entrez le nom du formateur">
                </div>
                <!-- Champ Prénom formateur -->
                <div class="form-group mt-1 mb-1">
                    <label class="label-form" for="prenomFormateur">Prénom du formateur</label>
                    <input class="form-control" id="prenomFormateur" name="prenomFormateur" type="text"
                           placeholder="Entrez le prénom du formateur">
                </div>
                <!-- Champ Téléphone formateur -->
                <div class="form-group mt-1 mb-1">
                    <label class="label-form" for="telephoneFormateur">Téléphone du formateur</label>
                    <input class="form-control" id="telephoneFormateur" name="telephoneFormateur" type="text"
                           placeholder="Entrez le téléphone du formateur">
                </div>
                <!-- Champ Sujet formation -->
                <div class="form-group mt-1 mb-1">
                    <label class="label-form" for="sujetFormation">Sujet de la formation</label>
                    <input class="form-control" id="sujetFormation" name="sujetFormation" type="text"
                           placeholder="Sujet de la formation">
                </div>
            </div>
        `;
        nextButton.textContent = 'Réserver'; // Changer le texte du bouton
        nextButton.setAttribute('type', 'submit'); // Modifier le type en "submit"
        currentStep++; // Passer à l'étape suivante
    }
});

// Ajouter un écouteur pour la soumission du formulaire
form.addEventListener('submit', (event) => {
    // Vous pouvez ajouter des actions ici avant la soumission réelle
    console.log('Formulaire soumis');
});



const toggleNav = e => {
    navbarToggler.classList.toggle("open");
    const ariaToggle = navbarToggler.getAttribute("aria-expanded") === "true" ? "false" : "true";
    navbarToggler.setAttribute("aria-expanded", ariaToggle);
    contentNavBar.classList.toggle("collapse");
};

navbarToggler.addEventListener("click", toggleNav);

const togglePasswordButton = document.getElementById("togglePassword");
const passwordInput = document.getElementById("motdepasse");
const passwordIcon = document.getElementById("passwordIcon");

// togglePasswordButton.addEventListener("click", () => {
//     if (passwordInput.type === "password") {
//         passwordInput.type = "text";
//         passwordIcon.classList.remove("fa-eye");
//         passwordIcon.classList.add("fa-eye-slash");
//     } else {
//         passwordInput.type = "password";
//         passwordIcon.classList.remove("fa-eye-slash");
//         passwordIcon.classList.add("fa-eye");
//     }
// });

/*
 * Gestion de la suppression d'un employé
 */
function creerModalSuppressionEmploye(){
    const button = event.target.closest('.btn-nav[title="Supprimer"]');

    // Met à jour le hash dans l'URL
    const employeeId = button.getAttribute('href').split('#')[1];
    window.location.hash = `#${employeeId}`;

    const reservationStatus = button.getAttribute('data-reservation'); // Vérifie la réservation

    // Récupère le modal et ses éléments
    const modal = document.getElementById('modal_supprimer_employe');
    const modalBody = modal.querySelector('.modal-body');
    const confirmButton = modal.querySelector('.btn-primary');

    // Modifie le contenu et le bouton du modal selon la réservation
    if (reservationStatus === 'true') {
        modalBody.innerHTML = "Cet employé ne peut pas être supprimé car il a des réservations actives.";
        confirmButton.style.display = 'none'; // Masquer le bouton "Supprimer"
    } else {
        modalBody.innerHTML = "Cet employé peut être supprimé.";
        confirmButton.style.display = 'inline-block'; // Afficher le bouton "Supprimer"
    }

    // Injecte l'ID dans le champ hidden du formulaire
    const hiddenInput = modal.querySelector('input[name="employeId"]');
    hiddenInput.value = employeeId;

    // Ouvre le modal
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
}

document.addEventListener('click', function (event) {
    if (event.target.closest('.btn-nav[title="Supprimer"]')) {
        event.preventDefault(); // Empêche le comportement par défaut

        creerModalSuppressionEmploye();
    }
});

// Gestion du hash au chargement de la page pour ouvrir le modal correspondant
document.addEventListener('DOMContentLoaded', () => {
    const currentHash = window.location.hash;
    if (currentHash) {
        const employeeId = currentHash.substring(1); // Retire le # pour obtenir l'ID

        creerModalSuppressionEmploye();
    }
});


// # Début Gestion des filtres

const boutonFiltrer = document.getElementById('btn-filtrer');
const menuDeroulant = document.getElementById('menu-deroulant');

// Fonction pour ouvrir/fermer le menu
boutonFiltrer.addEventListener('click', () => {
    const rect = boutonFiltrer.getBoundingClientRect();
    const windowHeight = window.innerHeight;

    // Calcul de la position (en dessous par défaut, au-dessus si pas assez de place)
    menuDeroulant.style.top = rect.bottom + window.scrollY + 'px'; // Par défaut, sous le bouton
    menuDeroulant.style.left = rect.left + window.scrollX + 'px';
    menuDeroulant.style.display = 'block'; // Afficher le menu

    const menuHeight = menuDeroulant.offsetHeight;

    // Vérifier si le menu dépasse la hauteur de la fenêtre
    if (rect.bottom + menuHeight > windowHeight) {
        menuDeroulant.style.top = rect.top + window.scrollY - menuHeight + 'px'; // Afficher au-dessus
    }
});

// Fermer le menu quand on clique ailleurs
document.addEventListener('click', (e) => {
    if (!menuDeroulant.contains(e.target) && e.target !== boutonFiltrer) {
        menuDeroulant.style.display = 'none';
    }
});

// Fermer le menu quand on clique sur le bouton "Annuler"
const boutonAnnuler = document.getElementById('btn-annuler');
boutonAnnuler.addEventListener('click', () => {
    menuDeroulant.style.display = 'none';
});

// # Fin Gestion des filtres



const navbarToggler = document.querySelector(".navbar-burger");
const contentNavBar = document.querySelector(".collapse");

const btnNext = document.getElementById('btn-next');
const btnBack = document.getElementById('btn-back');
const step1 = document.getElementById('step-1');
const step2 = document.getElementById('step-2');
const step3Sections = document.querySelectorAll('.step-3'); // Tous les blocs de l'étape 3
const modal = document.getElementById('ajouterReservation'); // Le modal lui-même
const typeReservation = document.getElementById('typeReservation'); // Sélecteur de type d'activité

let currentStep = 1;

// Initialisation : le bouton est affiché avec le texte "Fermer"
btnBack.textContent = 'Fermer';

// Fonction pour masquer toutes les sections de l'étape 3
function hideAllStep3Sections() {
    step3Sections.forEach(section => section.style.display = 'none');
}

// Fonction pour afficher la section de l'étape 3 appropriée
function showStep3Section() {
    const selectedType = typeReservation.value; // Valeur sélectionnée dans l'étape 2

    // Cacher toutes les étapes 3
    hideAllStep3Sections();

    // Afficher la bonne étape 3 en fonction de la sélection
    if (selectedType === "2") {
        document.getElementById('step-3-1').style.display = 'block';
    } else if (selectedType === "4" || selectedType === "5") {
        document.getElementById('step-3-2').style.display = 'block';
    } else {
        document.getElementById('step-3-default').style.display = 'block';
    }
}

// Fonction pour configurer le bouton "Suivant/Réserver"
function updateNextButton() {
    if (currentStep === 3) {
        btnNext.textContent = 'Réserver';
        btnNext.setAttribute('type', 'submit'); // Passer en mode soumission
    } else {
        btnNext.textContent = 'Suivant';
        btnNext.setAttribute('type', 'button'); // Rester en mode bouton normal
    }
}

// Fonction pour passer à l'étape suivante
btnNext.addEventListener('click', () => {
    if (currentStep === 1) {
        // Étape 1 -> Étape 2
        step1.style.display = 'none';
        step2.style.display = 'block';
        btnBack.textContent = 'Précédent'; // Change "Fermer" en "Précédent"
        currentStep++;
    } else if (currentStep === 2) {
        // Étape 2 -> Étape 3
        step2.style.display = 'none';

        // Afficher la bonne section de l'étape 3
        showStep3Section();

        currentStep++;
    }

    // Mettre à jour le bouton "Suivant/Réserver"
    updateNextButton();
});

// Fonction pour revenir à l'étape précédente ou fermer le modal
btnBack.addEventListener('click', () => {
    if (currentStep === 3) {
        // Étape 3 -> Étape 2
        hideAllStep3Sections(); // Cacher toutes les sections de l'étape 3
        step2.style.display = 'block';
        currentStep--;
    } else if (currentStep === 2) {
        // Étape 2 -> Étape 1
        step2.style.display = 'none';
        step1.style.display = 'block';
        btnBack.textContent = 'Fermer'; // Change "Précédent" en "Fermer"
        currentStep--;
    } else if (currentStep === 1) {
        // Fermer le modal à l'étape 1
        const bootstrapModal = bootstrap.Modal.getInstance(modal);
        bootstrapModal.hide(); // Ferme le modal
    }

    // Mettre à jour le bouton "Suivant/Réserver"
    updateNextButton();
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



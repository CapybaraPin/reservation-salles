const navbarToggler = document.querySelector(".navbar-burger");
const contentNavBar = document.querySelector(".collapse");

// Optionnel : Gérer l'ouverture automatique du modal si un fragment est présent dans l'URL
window.addEventListener('load', function () {
    const hash = window.location.hash.substring(1); // Supprime le `#`
    if (hash) {
        const button = document.querySelector(`.btn.btn-nav[href="#${hash}"]`);
        if (button) {
            ouvrirModalInformationSalle(button);
        }
    }
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

togglePasswordButton.addEventListener("click", () => {
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        passwordIcon.classList.remove("fa-eye");
        passwordIcon.classList.add("fa-eye-slash");
    } else {
        passwordInput.type = "password";
        passwordIcon.classList.remove("fa-eye-slash");
        passwordIcon.classList.add("fa-eye");
    }
});

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





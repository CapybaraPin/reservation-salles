const navbarToggler = document.querySelector(".navbar-burger")
const contentNavBar = document.querySelector(".collapse")

const toggleNav = e => {
    navbarToggler.classList.toggle("open")
    const ariaToggle = navbarToggler.getAttribute("aria-expanded") === "true" ? "false" : "true";
    navbarToggler.setAttribute("aria-expanded", ariaToggle)
    contentNavBar.classList.toggle("collapse")
}

navbarToggler.addEventListener("click", toggleNav);

const myModal = document.getElementById('myModal')
const myInput = document.getElementById('myInput')

const togglePasswordButton = document.getElementById("togglePassword");
const passwordInput = document.getElementById("motdepasse");
const passwordIcon = document.getElementById("passwordIcon");

togglePasswordButton.addEventListener("click", () => {
    // Vérifier le type actuel de l'input
    if (passwordInput.type === "password") {
        passwordInput.type = "text"; // Changer le type en 'text'
        passwordIcon.classList.remove("fa-eye"); // Icône oeil classique
        passwordIcon.classList.add("fa-eye-slash"); // Icône oeil barré
    } else {
        passwordInput.type = "password"; // Revenir à 'password'
        passwordIcon.classList.remove("fa-eye-slash"); // Retirer oeil barré
        passwordIcon.classList.add("fa-eye"); // Ajouter oeil classique
    }
});

// Lorsque le modal est ouvert
document.addEventListener('shown.bs.modal', function (event) {
    // Récupère le bouton qui a ouvert le modal
    const button = event.relatedTarget;

    // Récupère l'ID du modal à partir de l'attribut data-bs-target
    const modalId = button.getAttribute('data-bs-target');

    // Vérifie que l'ID est valide et correspond au modal ciblé
    if (modalId && modalId.startsWith('#modal_')) {
        // Récupère la valeur de data-reservation
        const reservationStatus = button.getAttribute('data-reservation');

        // Récupère les éléments spécifiques au modal ciblé
        const modal = document.querySelector(modalId);
        const modalBody = modal.querySelector('.modal-body');
        const confirmButton = modal.querySelector('.btn-primary');

        // Modifie le contenu et le bouton en fonction de la réservation
        if (reservationStatus === 'true') {
            modalBody.innerHTML = "Cet employé a des réservations. Vous ne pouvez donc pas le supprimer.";
            confirmButton.style.display = 'none'; // Masquer le bouton "Supprimer"
        } else {
            modalBody.innerHTML = "Êtes-vous sûr de vouloir supprimer cet employé ?";
            confirmButton.style.display = 'inline-block'; // Afficher le bouton "Supprimer" si nécessaire
        }
    }
});
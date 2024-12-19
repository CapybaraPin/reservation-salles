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

    // Récupère la valeur de data-reservation
    const reservationStatus = button.getAttribute('data-reservation');
    const modalId = button.getAttribute('data-bs-target');
    const confirmButton = document.querySelector(modalId + ' .btn-primary'); // Sélecteur pour le bouton de confirmation

    // Sélectionne le corps du modal
    const modalBody = document.querySelector(modalId + ' .modal-body');

    // Modifie le contenu du modal en fonction de la valeur de reservationStatus
    if (reservationStatus === 'true') {
        modalBody.innerHTML = "Cet employé a des réservations. Voulez-vous vraiment le supprimer ?";
        confirmButton.style.display = 'none';
    } else {
        modalBody.innerHTML = "Êtes-vous sûr de vouloir supprimer cet employé ?";
    }
});
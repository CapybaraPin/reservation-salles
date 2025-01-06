const navbarToggler = document.querySelector(".navbar-burger");
const contentNavBar = document.querySelector(".collapse");

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

if(togglePasswordButton != null) {
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
}

/*
 * Gestion de la suppression d'un employé
 */
function creerModalSuppressionEmploye(){
    const button = event.target.closest('.btn-nav[title="SupprimerEmploye"]');

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
    if (event.target.closest('.btn-nav[title="SupprimerEmploye"]')) {
        event.preventDefault(); // Empêche le comportement par défaut

        creerModalSuppressionEmploye();
    }
});

//Suppression d'une réservation
function creerModalSuppressionReservation(){

    const button = event.target.closest('.btn-nav[title="SupprimerReservation"]');

    const reservationID = button.getAttribute('href').split('#')[1];
    window.location.hash = `#${reservationID}`;

    // Récupère le modal et ses éléments
    const modal = document.getElementById('modal_supprimer_reservation');

    const hiddenInput = modal.querySelector('input[name="idReservation"]');
    hiddenInput.value = reservationID;

    // Ouvre le modal
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
}

document.addEventListener('click', function (event) {
    if (event.target.closest('.btn-nav[title="SupprimerReservation"]')) {
        event.preventDefault(); // Empêche le comportement par défaut

        creerModalSuppressionReservation();
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
document.addEventListener("DOMContentLoaded", function () {
    const selectFiltre = document.querySelector('select[name="nouveau_filtre[champ]"]');
    const inputText = document.getElementById("inputText");
    const inputDate = document.getElementById("inputDate");
    const inputDatetimeLocal = document.getElementById("inputDatetime-local");

    // Fonction pour mettre à jour la visibilité des inputs
    const updateInputVisibility = () => {
        const valeurSelectionnee = selectFiltre ? selectFiltre.value : null;

        // Réinitialisation des champs
        if (inputText) inputText.style.display = "none";
        if (inputDate) inputDate.style.display = "none";
        if (inputDatetimeLocal) inputDatetimeLocal.style.display = "none";

        // Afficher l'input correspondant
        if (valeurSelectionnee === "date" && inputDate) {
            inputDate.style.display = "block";
        } else if (valeurSelectionnee === "periode" && inputDatetimeLocal) {
            inputDatetimeLocal.style.display = "block";
        } else if (valeurSelectionnee && inputText) {
            inputText.style.display = "block";
        }
    };

    // Écoute des changements de la liste déroulante (si elle existe)
    if (selectFiltre) {
        selectFiltre.addEventListener("change", updateInputVisibility);
    }

    // Initialisation des champs (par défaut)
    updateInputVisibility();
});

// # Fin Gestion des filtres



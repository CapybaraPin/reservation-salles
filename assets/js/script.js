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
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

myModal.addEventListener('shown.bs.modal', () => {
    myInput.focus()
})
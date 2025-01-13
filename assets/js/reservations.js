// Méthode qui cache tous les éléments qui ne doivent pas être visibles
function annuleAffichageActivite(){
    typeDescription.style.display = 'none';
    typeFormation.style.display = 'none';
    typeLocation.style.display = 'none';
}

// Méthode qui permet de récupérer le type de réservation et de faire des actions en fonction
function changeReservationActivite(typeActivite) {
    annuleAffichageActivite();

    if (typeActivite == 4 || typeActivite == 5) {
        // Afficher l'organisation et sujet de location

        typeLocation.style.display = 'block';
    } else if (typeActivite == 2) {
        // Afficher le formateur et le sujet de formation

        typeFormation.style.display = 'block';
    } else {
        // Afficher la description

        typeDescription.style.display = 'block';
    }
}

const typeActivite = document.getElementById('typeActivite');

const typeDescription = document.getElementById('typeDescription');
const typeFormation = document.getElementById('typeFormation');
const typeLocation = document.getElementById('typeLocation');
typeActivite.addEventListener('change', (event) => {
    const typeActivite = event.target.value;
    changeReservationActivite(typeActivite);
});
document.addEventListener('DOMContentLoaded', () => {
    // Fonction pour afficher un message d'erreur
    const afficherErreur = (champ, message) => {
        champ.classList.add('is-invalid');
        if (!champ.nextElementSibling || !champ.nextElementSibling.classList.contains('invalid-feedback')) {
            const erreur = document.createElement('div');
            erreur.className = 'invalid-feedback';
            erreur.textContent = message;
            champ.parentNode.appendChild(erreur);
        }
    };

    // Fonction pour supprimer les messages d'erreur
    const supprimerErreur = (champ) => {
        champ.classList.remove('is-invalid');
        if (champ.nextElementSibling && champ.nextElementSibling.classList.contains('invalid-feedback')) {
            champ.parentNode.removeChild(champ.nextElementSibling);
        }
    };

    // Méthode de validation d'un formulaire
    const validerFormulaire = (champs, afficherErreurs = false) => {
        let estValide = true;

        champs.forEach(champ => {
            const { element, condition, messageErreur } = champ;

            if (afficherErreurs) {
                supprimerErreur(element);
            }

            if (!condition(element)) {
                if (afficherErreurs) {
                    afficherErreur(element, messageErreur);
                }
                estValide = false;
            }
        });

        return estValide;
    };

    // Gestion du clic sur le bouton "Suivant"
    const boutonSuivantSalle = document.querySelector('#boutonSuivantSalle');
    boutonSuivantSalle.addEventListener('click', (event) => {
        const champsSalle = [
            {
                element: document.getElementById('nom'),
                condition: (champ) => champ.value.trim() !== '',
                messageErreur: 'Veuillez entrer un nom valide.'
            },
            {
                element: document.getElementById('capacite'),
                condition: (champ) => champ.value.trim() !== '' && !isNaN(champ.value) && parseInt(champ.value) > 0,
                messageErreur: 'Veuillez entrer une capacité valide.'
            }
        ];

        if (!validerFormulaire(champsSalle, true)) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            // Transition vers la deuxième modal après validation
            const modalSalle = bootstrap.Modal.getInstance(document.getElementById('ajouterSalle'));
            if (modalSalle) modalSalle.hide();

            const modalOrdinateur = new bootstrap.Modal(document.getElementById('ajouterOrdinateur'));
            modalOrdinateur.show();
        }
    });

    // Gestion du clic sur le bouton "Envoyer"
    const boutonEnvoyerOrdinateur = document.querySelector('#ajouterOrdinateur button[type="submit"]');
    boutonEnvoyerOrdinateur.addEventListener('click', (event) => {
        const champsOrdinateur = [
            {
                element: document.getElementById('nbOrdinateurs'),
                condition: (champ) => champ.value.trim() !== '' && !isNaN(champ.value) && parseInt(champ.value) >= 0,
                messageErreur: 'Veuillez entrer un nombre d’ordinateurs valide.'
            },
            {
                element: document.getElementById('logiciels'),
                condition: (champ) => true, // Aucun logiciel n'est obligatoire
                messageErreur: '' // Pas d'erreur nécessaire
            },
            {
                element: document.getElementById('typeOrdinateur'),
                condition: (champ) => champ.value !== 'Sélectionnez un type d\'ordinateur',
                messageErreur: 'Veuillez sélectionner un type d’ordinateur.'
            }
        ];

        if (!validerFormulaire(champsOrdinateur, true)) {
            event.preventDefault();
        }
    });
});

// Validation du formulaire (Bootstrap)
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

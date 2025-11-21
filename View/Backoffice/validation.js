// Validation et contrôle de saisie pour le formulaire d'ajout d'offres
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    // Récupérer les champs du formulaire
    const nomInput = document.querySelector('input[name="nom"]');
    const descTextarea = document.querySelector('textarea[name="description"]');
    const prixInput = document.querySelector('input[name="prix"]');
    const categorieInput = document.querySelector('input[name="categorie"]');
    const categorieProbInput = document.querySelector('input[name="categorie_probleme"]');
    const contactInput = document.querySelector('input[name="contact"]');
    const imageInput = document.querySelector('input[name="image"]');
    
    // Fonction de validation du nom (sans caractères spéciaux)
    function validerNom(nom) {
        const regex = /^[a-zA-ZÀ-ÿ\s.\-']+$/;
        return regex.test(nom);
    }
    
    // Fonction de validation de l'email
    function validerEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
    
    // Fonction de validation du téléphone tunisien
    function validerTelephone(tel) {
        const regex = /^(\+216)?[2-9]\d{7}$/;
        return regex.test(tel.replace(/\s/g, ''));
    }
    
    // Fonction de validation du contact (email ou téléphone)
    function validerContact(contact) {
        return validerEmail(contact) || validerTelephone(contact);
    }
    
    // Fonction de validation du prix
    function validerPrix(prix) {
        return prix > 0 && prix <= 10000;
    }
    
    // Fonction de validation de la catégorie (sans caractères spéciaux sauf virgule)
    function validerCategorie(cat) {
        const regex = /^[a-zA-ZÀ-ÿ\s,\-]+$/;
        return regex.test(cat);
    }
    
    // Fonction de validation de l'URL d'image
    function validerImage(url) {
        if (!url || url.trim() === '') return true; // Optionnel
        const regex = /^(https?:\/\/.*\.(?:png|jpg|jpeg|gif|webp|svg)|assets\/.*\.(?:png|jpg|jpeg|gif|webp|svg))$/i;
        return regex.test(url);
    }
    
    // Afficher un message d'erreur
    function afficherErreur(input, message) {
        let errorDiv = input.parentElement.querySelector('.error-message');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.color = 'red';
            errorDiv.style.fontSize = '0.9em';
            errorDiv.style.marginTop = '5px';
            input.parentElement.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
        input.style.borderColor = 'red';
    }
    
    // Effacer un message d'erreur
    function effacerErreur(input) {
        const errorDiv = input.parentElement.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.remove();
        }
        input.style.borderColor = '';
    }
    
    // Validation en temps réel pour le nom
    if (nomInput) {
        nomInput.addEventListener('input', function() {
            if (this.value && !validerNom(this.value)) {
                afficherErreur(this, 'Le nom ne doit contenir que des lettres, espaces, points, tirets et apostrophes');
            } else {
                effacerErreur(this);
            }
        });
    }
    
    // Validation en temps réel pour le prix
    if (prixInput) {
        prixInput.addEventListener('input', function() {
            const prix = parseFloat(this.value);
            if (this.value && !validerPrix(prix)) {
                afficherErreur(this, 'Le prix doit être entre 0.01 et 10000 DT');
            } else {
                effacerErreur(this);
            }
        });
    }
    
    // Validation en temps réel pour les catégories
    if (categorieInput) {
        categorieInput.addEventListener('input', function() {
            if (this.value && !validerCategorie(this.value)) {
                afficherErreur(this, 'La catégorie ne doit contenir que des lettres, espaces, virgules et tirets');
            } else {
                effacerErreur(this);
            }
        });
    }
    
    if (categorieProbInput) {
        categorieProbInput.addEventListener('input', function() {
            if (this.value && !validerCategorie(this.value)) {
                afficherErreur(this, 'La catégorie problème ne doit contenir que des lettres, espaces, virgules et tirets');
            } else {
                effacerErreur(this);
            }
        });
    }
    
    // Validation en temps réel pour le contact
    if (contactInput) {
        contactInput.addEventListener('blur', function() {
            if (this.value && !validerContact(this.value)) {
                afficherErreur(this, 'Veuillez entrer un email valide ou un numéro de téléphone tunisien');
            } else {
                effacerErreur(this);
            }
        });
    }
    
    // Validation en temps réel pour l'image
    if (imageInput) {
        imageInput.addEventListener('blur', function() {
            if (this.value && !validerImage(this.value)) {
                afficherErreur(this, 'Veuillez entrer une URL d\'image valide (png, jpg, jpeg, gif, webp, svg)');
            } else {
                effacerErreur(this);
            }
        });
    }
    
    // Validation lors de la soumission du formulaire
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Valider le nom
        if (!nomInput.value || !validerNom(nomInput.value)) {
            afficherErreur(nomInput, 'Le nom est requis et ne doit contenir que des lettres');
            isValid = false;
        }
        
        // Valider la description (au moins 10 caractères)
        if (!descTextarea.value && descTextarea.value.length < 10) {
            afficherErreur(descTextarea, 'La description doit contenir au moins 10 caractères');
            isValid = false;
        }
        
        // Valider le prix
        const prix = parseFloat(prixInput.value);
        if (!prixInput.value || !validerPrix(prix)) {
            afficherErreur(prixInput, 'Le prix est requis et doit être entre 0.01 et 10000 DT');
            isValid = false;
        }
        
        // Valider la catégorie
        if (categorieInput.value && !validerCategorie(categorieInput.value)) {
            afficherErreur(categorieInput, 'La catégorie contient des caractères invalides');
            isValid = false;
        }
        
        // Valider la catégorie problème
        if (categorieProbInput.value && !validerCategorie(categorieProbInput.value)) {
            afficherErreur(categorieProbInput, 'La catégorie problème contient des caractères invalides');
            isValid = false;
        }
        
        // Valider le contact
        if (contactInput.value && !validerContact(contactInput.value)) {
            afficherErreur(contactInput, 'Veuillez entrer un email ou téléphone valide');
            isValid = false;
        }
        
        // Valider l'image
        if (imageInput.value && !validerImage(imageInput.value)) {
            afficherErreur(imageInput, 'URL d\'image invalide');
            isValid = false;
        }
        
        // Empêcher la soumission si invalide
        if (!isValid) {
            e.preventDefault();
            alert('Veuillez corriger les erreurs dans le formulaire avant de soumettre.');
        }
    });
});
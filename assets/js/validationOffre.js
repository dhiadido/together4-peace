document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    const nomInput = document.querySelector('input[name="nom"]');
    const descTextarea = document.querySelector('textarea[name="description"]');
    const prixInput = document.querySelector('input[name="prix"]');
    const categorieInput = document.querySelector('input[name="categorie"]');
    const categorieProbInput = document.querySelector('input[name="categorie_probleme"]');
    const contactInput = document.querySelector('input[name="contact"]');
    const imageInput = document.querySelector('input[name="image"]');
    
    function validerNom(nom) {
        const regex = /^[a-zA-ZÀ-ÿ\s.\-']+$/;
        return regex.test(nom);
    }
    
    function validerEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
    
    function validerTelephone(tel) {
        const regex = /^(\+216)?[2-9]\d{7}$/;
        return regex.test(tel.replace(/\s/g, ''));
    }
    
    function validerContact(contact) {
        return validerEmail(contact) || validerTelephone(contact);
    }

    function validerPrix(prix) {
        return prix > 0 && prix <= 10000;
    }
    
    function validerCategorie(cat) {
        const regex = /^[a-zA-ZÀ-ÿ\s,\-]+$/;
        return regex.test(cat);
    }
    
    function validerImage(url) {
        if (!url || url.trim() === '') return true;
        const regex = /^(https?:\/\/.*\.(?:png|jpg|jpeg|gif|webp|svg)|assets\/.*\.(?:png|jpg|jpeg|gif|webp|svg))$/i;
        return regex.test(url);
    }
    
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

    function effacerErreur(input) {
        const errorDiv = input.parentElement.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.remove();
        }
        input.style.borderColor = '';
    }
    
    if (nomInput) {
        nomInput.addEventListener('input', function() {
            if (this.value && !validerNom(this.value)) {
                afficherErreur(this, 'Le nom ne doit contenir que des lettres, espaces, points, tirets et apostrophes');
            } else {
                effacerErreur(this);
            }
        });
    }

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
    
    if (contactInput) {
        contactInput.addEventListener('blur', function() {
            if (this.value && !validerContact(this.value)) {
                afficherErreur(this, 'Veuillez entrer un email valide ou un numéro de téléphone tunisien');
            } else {
                effacerErreur(this);
            }
        });
    }
    
    if (imageInput) {
        imageInput.addEventListener('blur', function() {
            if (this.value && !validerImage(this.value)) {
                afficherErreur(this, 'Veuillez entrer une URL d\'image valide (png, jpg, jpeg, gif, webp, svg)');
            } else {
                effacerErreur(this);
            }
        });
    }
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        if (!nomInput.value || !validerNom(nomInput.value)) {
            afficherErreur(nomInput, 'Le nom est requis et ne doit contenir que des lettres');
            isValid = false;
        }
        
        if (!descTextarea.value && descTextarea.value.length < 10) {
            afficherErreur(descTextarea, 'La description doit contenir au moins 10 caractères');
            isValid = false;
        }
        
        const prix = parseFloat(prixInput.value);
        if (!prixInput.value || !validerPrix(prix)) {
            afficherErreur(prixInput, 'Le prix est requis et doit être entre 0.01 et 10000 DT');
            isValid = false;
        }
        
        if (categorieInput.value && !validerCategorie(categorieInput.value)) {
            afficherErreur(categorieInput, 'La catégorie contient des caractères invalides');
            isValid = false;
        }
        
        if (categorieProbInput.value && !validerCategorie(categorieProbInput.value)) {
            afficherErreur(categorieProbInput, 'La catégorie problème contient des caractères invalides');
            isValid = false;
        }
        
        if (contactInput.value && !validerContact(contactInput.value)) {
            afficherErreur(contactInput, 'Veuillez entrer un email ou téléphone valide');
            isValid = false;
        }
        
        if (imageInput.value && !validerImage(imageInput.value)) {
            afficherErreur(imageInput, 'URL d\'image invalide');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Veuillez corriger les erreurs dans le formulaire avant de soumettre.');
        }
    });
});

// Image preview
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<strong>Nouvelle image:</strong><br><img src="' + e.target.result + '" style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 10px;">';
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});
// Image preview
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
        }
        reader.readAsDataURL(file);
    }
});
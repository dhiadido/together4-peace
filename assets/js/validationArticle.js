document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    const titreInput = document.querySelector('input[name="titre"]');
    const themeInput = document.querySelector('input[name="theme"]');
    const resumeTextarea = document.querySelector('textarea[name="resume"]');
    const contenuTextarea = document.querySelector('textarea[name="contenu"]');
    const imageInput = document.querySelector('input[name="image"]');
    
    function validerTitre(titre) {
        return titre.length >= 5 && titre.length <= 200;
    }
    
    function validerTheme(theme) {
        const regex = /^[a-zA-ZÀ-ÿ\s,\-]+$/;
        return theme.length >= 5 && theme.length <= 100 && regex.test(theme);
    }
    
    function validerResume(resume) {
        if (!resume || resume.trim() === '') return true; // Optional
        return resume.length >= 10 && resume.length <= 500;
    }
    
    function validerContenu(contenu) {
        if (!contenu || contenu.trim() === '') return true; // Optional
        return contenu.length >= 20 && contenu.length <= 10000;
    }
    
    function validerImage(file) {
        if (!file) return true; // Optional
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        const maxSize = 5 * 1024 * 1024; // 5MB
        return allowedTypes.includes(file.type) && file.size <= maxSize;
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
    
    if (titreInput) {
        titreInput.addEventListener('input', function() {
            if (this.value && !validerTitre(this.value)) {
                afficherErreur(this, 'Le titre doit contenir entre 5 et 200 caractères');
            } else {
                effacerErreur(this);
            }
        });
    }

    if (themeInput) {
        themeInput.addEventListener('input', function() {
            if (this.value && !validerTheme(this.value)) {
                afficherErreur(this, 'Le thème doit contenir entre 5 et 100 caractères (lettres, espaces, virgules et tirets)');
            } else {
                effacerErreur(this);
            }
        });
    }
    
    if (resumeTextarea) {
        resumeTextarea.addEventListener('input', function() {
            if (this.value && !validerResume(this.value)) {
                afficherErreur(this, 'Le résumé doit contenir entre 10 et 500 caractères');
            } else {
                effacerErreur(this);
            }
        });
    }
    
    if (contenuTextarea) {
        contenuTextarea.addEventListener('input', function() {
            if (this.value && !validerContenu(this.value)) {
                afficherErreur(this, 'Le contenu doit contenir entre 20 et 10000 caractères');
            } else {
                effacerErreur(this);
            }
        });
    }
    
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file && !validerImage(file)) {
                afficherErreur(this, 'Format invalide ou fichier trop grand (max 5MB). Utilisez JPG, PNG, GIF ou WEBP');
            } else {
                effacerErreur(this);
            }
        });
    }
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        if (!titreInput.value || !validerTitre(titreInput.value)) {
            afficherErreur(titreInput, 'Le titre est requis et doit contenir entre 5 et 200 caractères');
            isValid = false;
        }
        
        if (!themeInput.value || !validerTheme(themeInput.value)) {
            afficherErreur(themeInput, 'Le thème est requis et doit contenir entre 5 et 100 caractères');
            isValid = false;
        }
        
        if (resumeTextarea.value && !validerResume(resumeTextarea.value)) {
            afficherErreur(resumeTextarea, 'Le résumé doit contenir entre 10 et 500 caractères');
            isValid = false;
        }
        
        if (contenuTextarea.value && !validerContenu(contenuTextarea.value)) {
            afficherErreur(contenuTextarea, 'Le contenu doit contenir entre 20 et 10000 caractères');
            isValid = false;
        }
        
        const file = imageInput.files[0];
        if (file && !validerImage(file)) {
            afficherErreur(imageInput, 'Format invalide ou fichier trop grand');
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
            preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
        }
        reader.readAsDataURL(file);
    }
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
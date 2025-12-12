document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    const titreInput = document.querySelector('input[name="titre"]');
    const themeInput = document.querySelector('input[name="theme"]');
    const resumeTextarea = document.querySelector('textarea[name="resume"]');
    const contenuTextarea = document.querySelector('textarea[name="contenu"]');
    const imageInput = document.querySelector('input[name="image"]');
    
    // Remove HTML5 validation attributes
    if (form) {
        form.setAttribute('novalidate', 'novalidate');
    }
    
    function validerTitre(titre) {
        if (!titre || titre.trim() === '') {
            return { valid: false, message: 'Le titre est requis' };
        }
        if (titre.length < 5) {
            return { valid: false, message: 'Le titre doit contenir au moins 5 caractères' };
        }
        if (titre.length > 200) {
            return { valid: false, message: 'Le titre ne peut pas dépasser 200 caractères' };
        }
        return { valid: true, message: '' };
    }
    
    function validerTheme(theme) {
        if (!theme || theme.trim() === '') {
            return { valid: false, message: 'Le thème est requis' };
        }
        const regex = /^[a-zA-ZÀ-ÿ\s,\-]+$/;
        if (theme.length < 5) {
            return { valid: false, message: 'Le thème doit contenir au moins 5 caractères' };
        }
        if (theme.length > 100) {
            return { valid: false, message: 'Le thème ne peut pas dépasser 100 caractères' };
        }
        if (!regex.test(theme)) {
            return { valid: false, message: 'Le thème ne peut contenir que des lettres, espaces, virgules et tirets' };
        }
        return { valid: true, message: '' };
    }
    
    function validerResume(resume) {
        if (!resume || resume.trim() === '') {
            return { valid: true, message: '' }; // Optional field
        }
        if (resume.length < 10) {
            return { valid: false, message: 'Le résumé doit contenir au moins 10 caractères' };
        }
        if (resume.length > 500) {
            return { valid: false, message: 'Le résumé ne peut pas dépasser 500 caractères' };
        }
        return { valid: true, message: '' };
    }
    
    function validerContenu(contenu) {
        if (!contenu || contenu.trim() === '') {
            return { valid: true, message: '' }; // Optional field
        }
        if (contenu.length < 20) {
            return { valid: false, message: 'Le contenu doit contenir au moins 20 caractères' };
        }
        if (contenu.length > 10000) {
            return { valid: false, message: 'Le contenu ne peut pas dépasser 10000 caractères' };
        }
        return { valid: true, message: '' };
    }
    
    function validerImage(file) {
        if (!file) {
            return { valid: true, message: '' }; // Optional field
        }
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!allowedTypes.includes(file.type)) {
            return { valid: false, message: 'Format invalide. Utilisez JPG, PNG, GIF ou WEBP' };
        }
        if (file.size > maxSize) {
            return { valid: false, message: 'Le fichier est trop grand (max 5MB)' };
        }
        return { valid: true, message: '' };
    }
    
    function afficherErreur(input, message) {
        let errorDiv = input.parentElement.querySelector('.error-message');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.color = '#dc3545';
            errorDiv.style.fontSize = '0.875em';
            errorDiv.style.marginTop = '5px';
            input.parentElement.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
        input.style.borderColor = '#dc3545';
        input.setAttribute('aria-invalid', 'true');
    }

    function effacerErreur(input) {
        const errorDiv = input.parentElement.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.remove();
        }
        input.style.borderColor = '';
        input.removeAttribute('aria-invalid');
    }
    
    // Real-time validation
    if (titreInput) {
        titreInput.addEventListener('blur', function() {
            const validation = validerTitre(this.value);
            if (!validation.valid) {
                afficherErreur(this, validation.message);
            } else {
                effacerErreur(this);
            }
        });
        
        titreInput.addEventListener('input', function() {
            const errorDiv = this.parentElement.querySelector('.error-message');
            if (errorDiv) {
                const validation = validerTitre(this.value);
                if (validation.valid) {
                    effacerErreur(this);
                } else {
                    afficherErreur(this, validation.message);
                }
            }
        });
    }

    if (themeInput) {
        themeInput.addEventListener('blur', function() {
            const validation = validerTheme(this.value);
            if (!validation.valid) {
                afficherErreur(this, validation.message);
            } else {
                effacerErreur(this);
            }
        });
        
        themeInput.addEventListener('input', function() {
            const errorDiv = this.parentElement.querySelector('.error-message');
            if (errorDiv) {
                const validation = validerTheme(this.value);
                if (validation.valid) {
                    effacerErreur(this);
                } else {
                    afficherErreur(this, validation.message);
                }
            }
        });
    }
    
    if (resumeTextarea) {
        resumeTextarea.addEventListener('blur', function() {
            const validation = validerResume(this.value);
            if (!validation.valid) {
                afficherErreur(this, validation.message);
            } else {
                effacerErreur(this);
            }
        });
        
        resumeTextarea.addEventListener('input', function() {
            const errorDiv = this.parentElement.querySelector('.error-message');
            if (errorDiv) {
                const validation = validerResume(this.value);
                if (validation.valid) {
                    effacerErreur(this);
                } else {
                    afficherErreur(this, validation.message);
                }
            }
        });
    }
    
    if (contenuTextarea) {
        contenuTextarea.addEventListener('blur', function() {
            const validation = validerContenu(this.value);
            if (!validation.valid) {
                afficherErreur(this, validation.message);
            } else {
                effacerErreur(this);
            }
        });
        
        contenuTextarea.addEventListener('input', function() {
            const errorDiv = this.parentElement.querySelector('.error-message');
            if (errorDiv) {
                const validation = validerContenu(this.value);
                if (validation.valid) {
                    effacerErreur(this);
                } else {
                    afficherErreur(this, validation.message);
                }
            }
        });
    }
    
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            const validation = validerImage(file);
            if (!validation.valid) {
                afficherErreur(this, validation.message);
            } else {
                effacerErreur(this);
            }
            
            // Image preview
            const preview = document.getElementById('imagePreview');
            if (preview && file && validation.valid) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<strong>Nouvelle image:</strong><br><img src="' + e.target.result + '" style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 10px;">';
                }
                reader.readAsDataURL(file);
            } else if (preview && !file) {
                preview.innerHTML = '';
            }
        });
    }
    
    // Form submission validation
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            let firstInvalidField = null;
            
            // Validate titre
            if (titreInput) {
                const titreValidation = validerTitre(titreInput.value);
                if (!titreValidation.valid) {
                    afficherErreur(titreInput, titreValidation.message);
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = titreInput;
                } else {
                    effacerErreur(titreInput);
                }
            }
            
            // Validate theme
            if (themeInput) {
                const themeValidation = validerTheme(themeInput.value);
                if (!themeValidation.valid) {
                    afficherErreur(themeInput, themeValidation.message);
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = themeInput;
                } else {
                    effacerErreur(themeInput);
                }
            }
            
            // Validate resume - OPTIONAL
            if (resumeTextarea) {
                const resumeValidation = validerResume(resumeTextarea.value);
                if (!resumeValidation.valid) {
                    afficherErreur(resumeTextarea, resumeValidation.message);
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = resumeTextarea;
                } else {
                    effacerErreur(resumeTextarea);
                }
            }
            
            // Validate contenu - OPTIONAL
            if (contenuTextarea) {
                const contenuValidation = validerContenu(contenuTextarea.value);
                if (!contenuValidation.valid) {
                    afficherErreur(contenuTextarea, contenuValidation.message);
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = contenuTextarea;
                } else {
                    effacerErreur(contenuTextarea);
                }
            }
            
            // Validate image - OPTIONAL
            if (imageInput) {
                const file = imageInput.files[0];
                const imageValidation = validerImage(file);
                if (!imageValidation.valid) {
                    afficherErreur(imageInput, imageValidation.message);
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = imageInput;
                } else {
                    effacerErreur(imageInput);
                }
            }
            
            if (!isValid) {
                e.preventDefault(); // Prevent submission only if invalid
                alert('Veuillez corriger les erreurs dans le formulaire avant de soumettre.');
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
            }
            // If valid, the form will submit naturally (no preventDefault)
        });
    }
});
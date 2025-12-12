document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    const nomInput = document.querySelector('input[name="nom"]');
    const descTextarea = document.querySelector('textarea[name="description"]');
    const prixInput = document.querySelector('input[name="prix"]');
    const categorieInput = document.querySelector('input[name="categorie"]');
    const categorieProbInput = document.querySelector('input[name="categorie_probleme"]');
    const contactInput = document.querySelector('input[name="contact"]');
    const imageInput = document.querySelector('input[name="image"]');
    
    // Remove HTML5 validation attributes
    if (form) {
        form.setAttribute('novalidate', 'novalidate');
    }
    
    function validerNom(nom) {
        if (!nom || nom.trim() === '') {
            return { valid: false, message: 'Le nom est requis' };
        }
        const regex = /^[a-zA-ZÀ-ÿ\s.\-']+$/;
        if (!regex.test(nom)) {
            return { valid: false, message: 'Le nom ne doit contenir que des lettres, espaces, points, tirets et apostrophes' };
        }
        return { valid: true, message: '' };
    }
    
    function validerDescription(desc) {
        if (!desc || desc.trim() === '') {
            return { valid: false, message: 'La description est requise' };
        }
        if (desc.length < 10) {
            return { valid: false, message: 'La description doit contenir au moins 10 caractères' };
        }
        if (desc.length > 1000) {
            return { valid: false, message: 'La description ne peut pas dépasser 1000 caractères' };
        }
        return { valid: true, message: '' };
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
        if (!contact || contact.trim() === '') {
            return { valid: true, message: '' }; // Optional field
        }
        if (!validerEmail(contact) && !validerTelephone(contact)) {
            return { valid: false, message: 'Veuillez entrer un email valide ou un numéro de téléphone tunisien' };
        }
        return { valid: true, message: '' };
    }

    function validerPrix(prix) {
        if (!prix || prix === '') {
            return { valid: false, message: 'Le prix est requis' };
        }
        const prixNum = parseFloat(prix);
        if (isNaN(prixNum)) {
            return { valid: false, message: 'Le prix doit être un nombre valide' };
        }
        if (prixNum <= 0) {
            return { valid: false, message: 'Le prix doit être supérieur à 0' };
        }
        if (prixNum > 10000) {
            return { valid: false, message: 'Le prix ne peut pas dépasser 10000 DT' };
        }
        return { valid: true, message: '' };
    }
    
    function validerCategorie(cat) {
        if (!cat || cat.trim() === '') {
            return { valid: true, message: '' }; // Optional field
        }
        const regex = /^[a-zA-ZÀ-ÿ\s,\-]+$/;
        if (!regex.test(cat)) {
            return { valid: false, message: 'La catégorie ne doit contenir que des lettres, espaces, virgules et tirets' };
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
    
    // Real-time validation for nom
    if (nomInput) {
        nomInput.addEventListener('blur', function() {
            const validation = validerNom(this.value);
            if (!validation.valid) {
                afficherErreur(this, validation.message);
            } else {
                effacerErreur(this);
            }
        });
        
        nomInput.addEventListener('input', function() {
            const errorDiv = this.parentElement.querySelector('.error-message');
            if (errorDiv) {
                const validation = validerNom(this.value);
                if (validation.valid) {
                    effacerErreur(this);
                } else {
                    afficherErreur(this, validation.message);
                }
            }
        });
    }

    // Real-time validation for description
    if (descTextarea) {
        descTextarea.addEventListener('blur', function() {
            const validation = validerDescription(this.value);
            if (!validation.valid) {
                afficherErreur(this, validation.message);
            } else {
                effacerErreur(this);
            }
        });
        
        descTextarea.addEventListener('input', function() {
            const errorDiv = this.parentElement.querySelector('.error-message');
            if (errorDiv) {
                const validation = validerDescription(this.value);
                if (validation.valid) {
                    effacerErreur(this);
                } else {
                    afficherErreur(this, validation.message);
                }
            }
        });
    }

    // Real-time validation for prix
    if (prixInput) {
        prixInput.addEventListener('blur', function() {
            const validation = validerPrix(this.value);
            if (!validation.valid) {
                afficherErreur(this, validation.message);
            } else {
                effacerErreur(this);
            }
        });
        
        prixInput.addEventListener('input', function() {
            const errorDiv = this.parentElement.querySelector('.error-message');
            if (errorDiv) {
                const validation = validerPrix(this.value);
                if (validation.valid) {
                    effacerErreur(this);
                } else {
                    afficherErreur(this, validation.message);
                }
            }
        });
    }
    
    // Real-time validation for categorie
    if (categorieInput) {
        categorieInput.addEventListener('blur', function() {
            const validation = validerCategorie(this.value);
            if (!validation.valid) {
                afficherErreur(this, validation.message);
            } else {
                effacerErreur(this);
            }
        });
        
        categorieInput.addEventListener('input', function() {
            const errorDiv = this.parentElement.querySelector('.error-message');
            if (errorDiv) {
                const validation = validerCategorie(this.value);
                if (validation.valid) {
                    effacerErreur(this);
                } else {
                    afficherErreur(this, validation.message);
                }
            }
        });
    }
    
    // Real-time validation for categorie_probleme
    if (categorieProbInput) {
        categorieProbInput.addEventListener('blur', function() {
            const validation = validerCategorie(this.value);
            if (!validation.valid) {
                afficherErreur(this, validation.message);
            } else {
                effacerErreur(this);
            }
        });
        
        categorieProbInput.addEventListener('input', function() {
            const errorDiv = this.parentElement.querySelector('.error-message');
            if (errorDiv) {
                const validation = validerCategorie(this.value);
                if (validation.valid) {
                    effacerErreur(this);
                } else {
                    afficherErreur(this, validation.message);
                }
            }
        });
    }
    
    // Real-time validation for contact
    if (contactInput) {
        contactInput.addEventListener('blur', function() {
            const validation = validerContact(this.value);
            if (!validation.valid) {
                afficherErreur(this, validation.message);
            } else {
                effacerErreur(this);
            }
        });
        
        contactInput.addEventListener('input', function() {
            const errorDiv = this.parentElement.querySelector('.error-message');
            if (errorDiv) {
                const validation = validerContact(this.value);
                if (validation.valid) {
                    effacerErreur(this);
                } else {
                    afficherErreur(this, validation.message);
                }
            }
        });
    }
    
    // Image validation and preview
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
            if (preview) {
                if (file && validation.valid) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = '<strong>Nouvelle image:</strong><br><img src="' + e.target.result + '" style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-top: 10px;">';
                    }
                    reader.readAsDataURL(file);
                } else if (!file) {
                    preview.innerHTML = '';
                }
            }
        });
    }
    
    // Form submission validation
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Always prevent default to handle validation
            
            let isValid = true;
            let firstInvalidField = null;
            
            // Validate nom
            const nomValidation = validerNom(nomInput.value);
            if (!nomValidation.valid) {
                afficherErreur(nomInput, nomValidation.message);
                isValid = false;
                if (!firstInvalidField) firstInvalidField = nomInput;
            } else {
                effacerErreur(nomInput);
            }
            
            // Validate description
            const descValidation = validerDescription(descTextarea.value);
            if (!descValidation.valid) {
                afficherErreur(descTextarea, descValidation.message);
                isValid = false;
                if (!firstInvalidField) firstInvalidField = descTextarea;
            } else {
                effacerErreur(descTextarea);
            }
            
            // Validate prix
            const prixValidation = validerPrix(prixInput.value);
            if (!prixValidation.valid) {
                afficherErreur(prixInput, prixValidation.message);
                isValid = false;
                if (!firstInvalidField) firstInvalidField = prixInput;
            } else {
                effacerErreur(prixInput);
            }
            
            // Validate categorie
            if (categorieInput) {
                const categorieValidation = validerCategorie(categorieInput.value);
                if (!categorieValidation.valid) {
                    afficherErreur(categorieInput, categorieValidation.message);
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = categorieInput;
                } else {
                    effacerErreur(categorieInput);
                }
            }
            
            // Validate categorie_probleme
            if (categorieProbInput) {
                const categorieProbValidation = validerCategorie(categorieProbInput.value);
                if (!categorieProbValidation.valid) {
                    afficherErreur(categorieProbInput, categorieProbValidation.message);
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = categorieProbInput;
                } else {
                    effacerErreur(categorieProbInput);
                }
            }
            
            // Validate contact
            if (contactInput) {
                const contactValidation = validerContact(contactInput.value);
                if (!contactValidation.valid) {
                    afficherErreur(contactInput, contactValidation.message);
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = contactInput;
                } else {
                    effacerErreur(contactInput);
                }
            }
            
            // Validate image
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
                alert('Veuillez corriger les erreurs dans le formulaire avant de soumettre.');
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
            } else {
                // If all validations pass, submit the form
                this.submit();
            }
        });
    }
});
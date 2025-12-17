document.addEventListener('DOMContentLoaded', function () {
  const nomInput = document.getElementById('nom');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const confirmPasswordInput = document.getElementById('confirm_password');
  const matchMsg = document.getElementById('matchMsg');
  const photoInput = document.getElementById('photo');
  const preview = document.getElementById('preview');
  const editProfileForm = document.getElementById('editProfileForm');

  // Validation du nom (non vide)
  if (nomInput) {
    nomInput.addEventListener('blur', function () {
      const nom = this.value.trim();
      if (nom === '') {
        this.style.borderColor = '#dc3545';
        this.setCustomValidity('Le nom est requis.');
      } else {
        this.style.borderColor = '';
        this.setCustomValidity('');
      }
    });
  }

  // Validation de l'email (pas d'espaces)
  if (emailInput) {
    emailInput.addEventListener('input', function () {
      const email = this.value;
      if (email.includes(' ')) {
        this.value = email.replace(/\s/g, '');
        this.style.borderColor = '#dc3545';
        this.setCustomValidity('L\'adresse email ne doit pas contenir d\'espaces.');
      } else {
        this.style.borderColor = '';
        this.setCustomValidity('');
      }
    });

    emailInput.addEventListener('blur', function () {
      const email = this.value.trim();
      if (email === '') {
        this.style.borderColor = '#dc3545';
        this.setCustomValidity('L\'adresse email est requise.');
      } else if (email.includes(' ')) {
        this.style.borderColor = '#dc3545';
        this.setCustomValidity('L\'adresse email ne doit pas contenir d\'espaces.');
      } else {
        this.style.borderColor = '';
        this.setCustomValidity('');
      }
    });
  }

  // Validation de la correspondance des mots de passe
  function checkPasswordMatch() {
    if (!passwordInput || !confirmPasswordInput || !matchMsg) {
      return;
    }

    const password = passwordInput.value;
    const confirm = confirmPasswordInput.value;

    // Si le champ mot de passe est vide, on ne vérifie pas
    if (password === '') {
      matchMsg.textContent = '';
      matchMsg.className = 'password-match';
      confirmPasswordInput.setCustomValidity('');
      return;
    }

    // Si le champ confirmation est vide, on ne vérifie pas encore
    if (confirm === '') {
      matchMsg.textContent = '';
      matchMsg.className = 'password-match';
      return;
    }

    // Vérifier la correspondance
    if (password === confirm) {
      matchMsg.textContent = '✓ Les mots de passe correspondent';
      matchMsg.className = 'password-match success';
      confirmPasswordInput.setCustomValidity('');
    } else {
      matchMsg.textContent = '✗ Les mots de passe ne correspondent pas';
      matchMsg.className = 'password-match error';
      confirmPasswordInput.setCustomValidity('Les mots de passe ne correspondent pas.');
    }
  }

  if (passwordInput && confirmPasswordInput) {
    passwordInput.addEventListener('input', checkPasswordMatch);
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
  }

  // Aperçu de la photo
  if (photoInput && preview) {
    photoInput.addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (evt) {
          preview.innerHTML = `<img src="${evt.target.result}" class="img-preview" alt="Aperçu de la photo">`;
        };
        reader.readAsDataURL(file);
      } else {
        preview.innerHTML = '';
      }
    });
  }

  // Validation du formulaire avant soumission
  if (editProfileForm) {
    editProfileForm.addEventListener('submit', function (e) {
      // Validation du nom
      if (nomInput) {
        const nom = nomInput.value.trim();
        if (nom === '') {
          e.preventDefault();
          alert('Le nom est requis.');
          nomInput.focus();
          return false;
        }
      }

      // Validation de l'email
      if (emailInput) {
        const email = emailInput.value.trim();
        if (email === '') {
          e.preventDefault();
          alert('L\'adresse email est requise.');
          emailInput.focus();
          return false;
        }
        if (email.includes(' ')) {
          e.preventDefault();
          alert('L\'adresse email ne doit pas contenir d\'espaces.');
          emailInput.focus();
          return false;
        }
      }

      // Validation du mot de passe si fourni
      if (passwordInput && confirmPasswordInput) {
        const password = passwordInput.value;
        const confirm = confirmPasswordInput.value;

        if (password !== '' || confirm !== '') {
          if (password === '') {
            e.preventDefault();
            alert('Veuillez saisir un nouveau mot de passe.');
            passwordInput.focus();
            return false;
          }

          if (password !== confirm) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
            confirmPasswordInput.focus();
            return false;
          }

          if (password.length < 6) {
            e.preventDefault();
            alert('Le mot de passe doit contenir au moins 6 caractères.');
            passwordInput.focus();
            return false;
          }
        }
      }
    });
  }
});


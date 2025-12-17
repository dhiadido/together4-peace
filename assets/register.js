document.addEventListener('DOMContentLoaded', function () {
  const password = document.getElementById('password');
  const confirm = document.getElementById('confirm');
  const matchMsg = document.getElementById('matchMsg');
  const photoInput = document.getElementById('photo');
  const preview = document.getElementById('preview');
  const registerForm = document.getElementById('registerForm');

  function checkPasswordMatch() {
    if (!password || !confirm || !matchMsg) {
      return;
    }

    if (password.value && confirm.value) {
      if (password.value === confirm.value) {
        matchMsg.textContent = '✓ Les mots de passe correspondent';
        matchMsg.className = 'password-match success';
      } else {
        matchMsg.textContent = '✗ Les mots de passe ne correspondent pas';
        matchMsg.className = 'password-match error';
      }
    } else {
      matchMsg.textContent = '';
      matchMsg.className = 'password-match';
    }
  }

  if (password && confirm) {
    password.addEventListener('input', checkPasswordMatch);
    confirm.addEventListener('input', checkPasswordMatch);
  }

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

  // Validation du nom d'utilisateur (non vide)
  const usernameInput = document.getElementById('username');
  if (usernameInput) {
    usernameInput.addEventListener('blur', function () {
      const username = this.value.trim();
      if (username === '') {
        this.style.borderColor = '#dc3545';
        this.setCustomValidity('Le nom d\'utilisateur est requis.');
      } else {
        this.style.borderColor = '';
        this.setCustomValidity('');
      }
    });
  }

  // Validation de l'email (pas d'espaces)
  const emailInput = document.getElementById('email');
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

  if (registerForm) {
    registerForm.addEventListener('submit', function (e) {
      // Validation du nom d'utilisateur
      if (usernameInput) {
        const username = usernameInput.value.trim();
        if (username === '') {
          e.preventDefault();
          alert('Le nom d\'utilisateur est requis.');
          usernameInput.focus();
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

      // Validate reCAPTCHA when available
      if (typeof grecaptcha !== 'undefined') {
        const recaptchaResponse = grecaptcha.getResponse();
        if (!recaptchaResponse) {
          e.preventDefault();
          alert('Veuillez compléter la vérification reCAPTCHA.');
          return false;
        }
      }

      if (password && confirm && password.value !== confirm.value) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas.');
        return false;
      }
    });
  }
});


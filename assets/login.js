// assets/login.js

document.addEventListener('DOMContentLoaded', function () {
  const showPasswordCheckbox = document.getElementById('showPassword');
  const passwordField = document.getElementById('password');
  const loginForm = document.getElementById('loginForm');

  if (showPasswordCheckbox && passwordField) {
    showPasswordCheckbox.addEventListener('change', function () {
      passwordField.type = this.checked ? 'text' : 'password';
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

  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
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
    });
  }
});

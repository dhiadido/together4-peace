document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form[action="../controlleur/forgot_password_traitement.php"]');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    const emailInput = document.getElementById('email');
    if (!emailInput) return;

    emailInput.value = emailInput.value.trim();
    
    if (emailInput.value === '') {
      e.preventDefault();
      alert('Veuillez saisir votre adresse e-mail.');
      emailInput.focus();
      return false;
    }

    // Vérifier qu'il n'y a pas d'espaces dans l'email
    if (emailInput.value.includes(' ')) {
      e.preventDefault();
      alert('L\'adresse email ne doit pas contenir d\'espaces.');
      emailInput.focus();
      return false;
    }
  });

  // Validation en temps réel de l'email (pas d'espaces)
  const emailInput = document.getElementById('email');
  if (emailInput) {
    emailInput.addEventListener('input', function () {
      const email = this.value;
      if (email.includes(' ')) {
        this.value = email.replace(/\s/g, '');
        this.style.borderColor = '#dc3545';
      } else {
        this.style.borderColor = '';
      }
    });
  }
});


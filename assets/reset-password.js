document.addEventListener('DOMContentLoaded', function () {
  const password = document.getElementById('password');
  const confirm = document.getElementById('confirm');
  const strength = document.getElementById('strength');
  const matchMsg = document.getElementById('matchMsg');

  if (password && strength) {
    password.addEventListener('input', function () {
      const pwd = password.value;
      let strengthValue = 0;

      if (pwd.length >= 6) strengthValue += 33;
      if (pwd.length >= 8) strengthValue += 33;
      if (/[A-Z]/.test(pwd) && /[a-z]/.test(pwd)) strengthValue += 34;

      strength.className = 'password-strength';
      if (strengthValue < 33) {
        strength.classList.add('weak');
      } else if (strengthValue < 66) {
        strength.classList.add('medium');
      } else {
        strength.classList.add('strong');
      }
    });
  }

  if (confirm && matchMsg && password) {
    confirm.addEventListener('input', function () {
      if (confirm.value === '') {
        matchMsg.textContent = '';
        matchMsg.className = 'password-match';
        return;
      }

      if (confirm.value === password.value) {
        matchMsg.textContent = '✓ Les mots de passe correspondent';
        matchMsg.className = 'password-match success';
      } else {
        matchMsg.textContent = '✗ Les mots de passe ne correspondent pas';
        matchMsg.className = 'password-match error';
      }
    });
  }
});


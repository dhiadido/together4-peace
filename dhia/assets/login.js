// assets/login.js

const showPasswordCheckbox = document.getElementById('showPassword');
const passwordField        = document.getElementById('password');

if (showPasswordCheckbox && passwordField) {
  showPasswordCheckbox.addEventListener('change', function () {
    passwordField.type = this.checked ? 'text' : 'password';
  });
}

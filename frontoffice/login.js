// ==== Afficher / masquer le mot de passe ====
const passwordInput = document.getElementById('password');
const showPassword = document.getElementById('showPassword');

showPassword.addEventListener('change', () => {
  passwordInput.type = showPassword.checked ? 'text' : 'password';
});

// ==== Validation simple avant envoi ====
document.getElementById('loginForm').addEventListener('submit', function (e) {
  const email = document.getElementById('email').value.trim();
  const password = passwordInput.value.trim();

  if (email === "" || password === "") {
    e.preventDefault();
    alert("Veuillez remplir tous les champs !");
  }
});

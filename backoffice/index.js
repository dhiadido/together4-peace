// ===== Vérification de la correspondance des mots de passe =====
const password = document.getElementById('password');
const confirm = document.getElementById('confirm');
const matchMsg = document.getElementById('matchMsg');

function checkPasswords() {
  if (confirm.value.length == 0) {
    matchMsg.textContent = "";
    return;
  }
  if (password.value == confirm.value) {
    matchMsg.textContent = "✅ Les mots de passe correspondent";
    matchMsg.className = "password-match match";
  } else {
    matchMsg.textContent = "❌ Les mots de passe ne correspondent pas";
    matchMsg.className = "password-match no-match";
  }
}

password.addEventListener('input', checkPasswords);
confirm.addEventListener('input', checkPasswords);

// ===== Prévisualisation de la photo =====
const photo = document.getElementById('photo');
const preview = document.getElementById('preview');

photo.addEventListener('change', () => {
  const file = photo.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = () => {
      preview.src = reader.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
  } else {
    preview.style.display = 'none';
  }
});

// ===== Validation finale avant envoi =====
document.getElementById('registerForm').addEventListener('submit', function (e) {
  if (password.value !== confirm.value) {
    e.preventDefault();
    alert("Les mots de passe ne correspondent pas !");
  }
});

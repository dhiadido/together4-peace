// assets/index.js

const passwordInput = document.getElementById('password');
const confirmInput  = document.getElementById('confirm');
const matchMsg      = document.getElementById('matchMsg');
const form          = document.getElementById('registerForm');

const photoInput = document.getElementById('photo');
const previewImg = document.getElementById('preview');

function checkPasswords() {
  if (passwordInput.value === '' || confirmInput.value === '') {
    matchMsg.textContent = '';
    return;
  }

  if (passwordInput.value === confirmInput.value) {
    matchMsg.textContent = 'Les mots de passe correspondent ✅';
    matchMsg.style.color = 'limegreen';
  } else {
    matchMsg.textContent = 'Les mots de passe ne correspondent pas ❌';
    matchMsg.style.color = 'red';
  }
}

passwordInput.addEventListener('input', checkPasswords);
confirmInput.addEventListener('input', checkPasswords);

form.addEventListener('submit', function (e) {
  if (passwordInput.value !== confirmInput.value) {
    e.preventDefault();
    alert("Les mots de passe ne correspondent pas.");
  }
});

// Preview image
if (photoInput && previewImg) {
  photoInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) {
      previewImg.style.display = 'none';
      return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
      previewImg.src = e.target.result;
      previewImg.style.display = 'block';
    };
    reader.readAsDataURL(file);
  });
}

document.addEventListener('DOMContentLoaded', function () {
  const editPanel = document.getElementById('edit-panel');
  const editId = document.getElementById('edit-id');
  const editNom = document.getElementById('edit-nom');
  const editPrenom = document.getElementById('edit-prenom');
  const editEmail = document.getElementById('edit-email');
  const editRole = document.getElementById('edit-role');
  const createForm = document.getElementById('create-user-form');

  // Validation du nom (non vide) pour le formulaire de création
  const createNom = document.getElementById('create-nom');
  if (createNom) {
    createNom.addEventListener('blur', function () {
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

  // Validation de l'email (pas d'espaces) pour le formulaire de création
  const createEmail = document.getElementById('create-email');
  if (createEmail) {
    createEmail.addEventListener('input', function () {
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
  }

  // Validation du nom (non vide) pour le formulaire d'édition
  if (editNom) {
    editNom.addEventListener('blur', function () {
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

  // Validation de l'email (pas d'espaces) pour le formulaire d'édition
  if (editEmail) {
    editEmail.addEventListener('input', function () {
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
  }

  // Validation du formulaire de création
  if (createForm) {
    createForm.addEventListener('submit', function (e) {
      if (createNom) {
        const nom = createNom.value.trim();
        if (nom === '') {
          e.preventDefault();
          alert('Le nom est requis.');
          createNom.focus();
          return false;
        }
      }

      if (createEmail) {
        const email = createEmail.value.trim();
        if (email === '') {
          e.preventDefault();
          alert('L\'adresse email est requise.');
          createEmail.focus();
          return false;
        }
        if (email.includes(' ')) {
          e.preventDefault();
          alert('L\'adresse email ne doit pas contenir d\'espaces.');
          createEmail.focus();
          return false;
        }
      }
    });
  }

  // Validation du formulaire d'édition
  const editForm = document.getElementById('edit-user-form');
  if (editForm) {
    editForm.addEventListener('submit', function (e) {
      if (editNom) {
        const nom = editNom.value.trim();
        if (nom === '') {
          e.preventDefault();
          alert('Le nom est requis.');
          editNom.focus();
          return false;
        }
      }

      if (editEmail) {
        const email = editEmail.value.trim();
        if (email === '') {
          e.preventDefault();
          alert('L\'adresse email est requise.');
          editEmail.focus();
          return false;
        }
        if (email.includes(' ')) {
          e.preventDefault();
          alert('L\'adresse email ne doit pas contenir d\'espaces.');
          editEmail.focus();
          return false;
        }
      }
    });
  }

  document.querySelectorAll('.btn-edit-user').forEach((btn) => {
    btn.addEventListener('click', function () {
      const { id, nom, prenom, email, role } = this.dataset;
      if (editPanel && editId && editNom && editPrenom && editEmail && editRole) {
        editPanel.style.display = 'block';
        editId.value = id;
        editNom.value = nom || '';
        editPrenom.value = prenom || '';
        editEmail.value = email || '';
        editRole.value = role || 'user';
        editPanel.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });
});


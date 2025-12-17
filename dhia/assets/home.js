document.addEventListener("DOMContentLoaded", () => {
  // Nom de l'utilisateur
  const usernameDisplay = document.getElementById("usernameDisplay");
  const username = localStorage.getItem("username") || "Utilisateur";
  usernameDisplay.textContent = username;

  // Bouton déconnexion
  const logoutBtn = document.getElementById("logoutBtn");
  logoutBtn.addEventListener("click", () => {
    if (confirm("Voulez-vous vraiment vous déconnecter ?")) {
      localStorage.removeItem("username");
      window.location.href = "login.php";
    }
  });

  // Animation fade-in pour articles et cartes
  const fadeElements = document.querySelectorAll("article, .card-item");
  fadeElements.forEach((el, index) => {
    setTimeout(() => {
      el.classList.add("fade-in");
    }, index * 300); // cascade
  });
});
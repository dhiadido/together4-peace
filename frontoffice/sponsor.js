document.addEventListener("DOMContentLoaded", () => {
  const sponsors = document.querySelectorAll(".sponsor-logo");

  // Effet d'apparition progressive
  sponsors.forEach((logo, index) => {
    logo.style.opacity = 0;
    logo.style.transition = `opacity 1s ease ${index * 0.5}s`; // décalage progressif
  });

  // Appliquer l'apparition
  setTimeout(() => {
    sponsors.forEach(logo => {
      logo.style.opacity = 1;
    });
  }, 100);
});

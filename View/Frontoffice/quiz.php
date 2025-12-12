<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Together4Peace - Bâtir des Ponts. Non des Murs</title>
  <link rel="stylesheet" href="..\..\assets\css\styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <?php include "templateFront.php"; ?>

  <div align="center">
<div class="container">
  <section class="quiz">
    <h2>Simulateur de score</h2>
    <p>Choisis un score pour simuler le résultat du quiz.</p>
    <input id="scoreInput" type="range" min="0" max="100" value="55">
    <div class="score-area">
      <span id="scoreValue">55</span>%
    </div>
    <button id="viewOffers">Voir recommandations</button>
    <p class="hint">Si le score est inférieur à 40% tu verras des offres adaptées.</p>
  </section>

  <section id="offersContainer" class="offers hidden">
    
  </section>
</div>

<div id="modal" class="modal hidden" role="dialog" aria-hidden="true">
  <div class="modal-content">
    <button class="modal-close" id="modalClose">×</button>
    <div id="modalBody"></div>
  </div>
</div>
</div>
<section class="footer-section">
    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <img src="..\..\assets\images\logo.png" alt="Logo Together4Peace" class="header-logo footer-logo-img">
                <span class="site-name">Together4Peace</span>
            </div>
            <div class="footer-links">
                <h4>Liens Utiles</h4>
                <ul>
                    <li><a href="about.html">Notre Mission</a></li>
                    <li><a href="charter.html">La Charte</a></li>
                    <li><a href="offers.html">Nos Offres</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="footer-social">
                <h4>Suivez-nous</h4>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2025 Together4Peace. Tous droits réservés. | Mentions Légales
        </div>
    </footer>
    </section>

<script src="..\..\assets\js\scriptOffre.js"></script>
</body>
</html>



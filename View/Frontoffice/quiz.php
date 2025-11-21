<!-- View/Frontoffice/quiz.php -->

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
    <!-- Les offres s'afficheront ici (via AJAX vers offres_specialistes.php) -->
  </section>
</div>

<div id="modal" class="modal hidden" role="dialog" aria-hidden="true">
  <div class="modal-content">
    <button class="modal-close" id="modalClose">×</button>
    <div id="modalBody"></div>
  </div>
</div>

<script src="script.js"></script>
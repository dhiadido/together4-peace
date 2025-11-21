document.addEventListener('DOMContentLoaded', function(){
  const scoreInput = document.getElementById('scoreInput');
  const scoreValue = document.getElementById('scoreValue');
  const viewOffers = document.getElementById('viewOffers');
  const offersContainer = document.getElementById('offersContainer');
  const modal = document.getElementById('modal');
  const modalClose = document.getElementById('modalClose');
  const modalBody = document.getElementById('modalBody');

  scoreInput.addEventListener('input', ()=> scoreValue.textContent = scoreInput.value);

  viewOffers.addEventListener('click', ()=>{
    const score = parseInt(scoreInput.value,10);
    if(score < 40){
      // fetch offers via GET to offres_specialistes.php (simulé)
      fetch('offres_specialistes.php?score=' + score)
        .then(r=>r.text())
        .then(html=>{
          offersContainer.innerHTML = html;
          offersContainer.classList.remove('hidden');
        })
        .catch(err=>{
          offersContainer.innerHTML = '<div class="card"><p>Erreur lors du chargement.</p></div>';
          offersContainer.classList.remove('hidden');
        });
    } else {
      offersContainer.innerHTML = '<div class="card"><h3>Bravo — votre score est suffisant !</h3><p>Pas d\'offres nécessaires.</p></div>';
      offersContainer.classList.remove('hidden');
    }
  });

  modalClose.addEventListener('click', ()=>{ modal.classList.add('hidden'); modal.setAttribute('aria-hidden','true'); });
  window.showOfferModal = function(html){
    modalBody.innerHTML = html;
    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden','false');
  }
});

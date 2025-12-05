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
      // 🔥 Fetch SMART RECOMMENDATIONS with score parameter
      fetch('../../View/Frontoffice/Articles.php?score=' + score)
        .then(r=>r.text())
        .then(html=>{
          offersContainer.innerHTML = html;
          offersContainer.classList.remove('hidden');
        })
        .catch(err=>{
          console.error('Fetch error:', err);
          offersContainer.innerHTML = '<div class="card"><p>Erreur lors du chargement des articles.</p></div>';
          offersContainer.classList.remove('hidden');
        });
    } else {
      offersContainer.innerHTML = '<div class="success-message"><h3>🎉 Bravo — votre score est suffisant !</h3><p>Vous avez une bonne compréhension des enjeux de paix. Continuez comme ça !</p></div>';
      offersContainer.classList.remove('hidden');
    }
  });

  modalClose.addEventListener('click', ()=>{ 
    modal.classList.add('hidden'); 
    modal.setAttribute('aria-hidden','true'); 
  });

  // 🔥 Enhanced function with tracking
  window.showOfferModal = function(offreId){
    // Track the click
    fetch('../../Controller/trackClick.php?offre_id=' + offreId, {method: 'POST'})
      .catch(err => console.log('Tracking failed:', err));
    
    // Show modal
    fetch('../../View/Frontoffice/Offres.php?id=' + offreId)
      .then(r=>r.text())
      .then(html=>{
        modalBody.innerHTML = html;
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden','false');
      })
      .catch(err=>{
        console.error('Modal fetch error:', err);
        modalBody.innerHTML = '<p>Erreur lors du chargement des détails.</p>';
        modal.classList.remove('hidden');
      });
  }
});
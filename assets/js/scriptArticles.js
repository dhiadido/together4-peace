// Modal functionality for Articles page
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal');
    const modalClose = document.getElementById('modalClose');
    const modalBody = document.getElementById('modalBody');

    if (modalClose) {
        modalClose.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
        });
    }

    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modalClose.click();
            }
        });
    }
});

window.showOfferModal = function(offreId) {
    const modal = document.getElementById('modal');
    const modalBody = document.getElementById('modalBody');
    
    fetch('Offres.php?id=' + offreId)
        .then(r => r.text())
        .then(html => {
            modalBody.innerHTML = html;
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
        })
        .catch(err => {
            console.error('Modal fetch error:', err);
            modalBody.innerHTML = '<p>Erreur lors du chargement des détails.</p>';
            modal.classList.remove('hidden');
        });
}
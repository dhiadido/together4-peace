<?php
require_once(__DIR__ . '/../../Controller/OffreController.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo '<p>ID invalide.</p>';
    exit;
}

$controller = new OffreController();
$offre = $controller->getOffreWithArticle($id);

if (!$offre) {
    echo '<p>Offre introuvable.</p>';
    exit;
}
?>

<div class="modal-offre-details">
    <div class="modal-header">
        <?php if (!empty($offre['image'])): ?>
            <img src="<?= htmlspecialchars($offre['image']) ?>" 
                 alt="<?= htmlspecialchars($offre['nom_specialiste']) ?>"
                 class="modal-offre-image">
        <?php endif; ?>
        <div class="modal-offre-title">
            <h2><?= htmlspecialchars($offre['nom_specialiste']) ?></h2>
            <span class="modal-category"><?= htmlspecialchars($offre['categorie']) ?></span>
        </div>
    </div>

    <div class="modal-body">
        <div class="modal-section">
            <h3>📝 Description</h3>
            <p><?= nl2br(htmlspecialchars($offre['description'])) ?></p>
        </div>

        <div class="modal-info-grid">
            <div class="modal-info-item">
                <i class="fas fa-tag"></i>
                <div>
                    <strong>Catégorie problème</strong>
                    <p><?= htmlspecialchars($offre['categorie_probleme']) ?></p>
                </div>
            </div>

            <div class="modal-info-item">
                <i class="fas fa-money-bill-wave"></i>
                <div>
                    <strong>Prix</strong>
                    <p class="modal-price"><?= number_format($offre['prix'], 2) ?> DT</p>
                </div>
            </div>

            <div class="modal-info-item">
                <i class="fas fa-phone"></i>
                <div>
                    <strong>Contact</strong>
                    <p><?= htmlspecialchars($offre['contact']) ?></p>
                </div>
            </div>
        </div>

        <?php if (!empty($offre['titre'])): ?>
            <div class="modal-section related-article">
                <h3>📚 Article associé</h3>
                <div class="article-badge">
                    <strong><?= htmlspecialchars($offre['titre']) ?></strong>
                    <span class="article-theme-badge"><?= htmlspecialchars($offre['theme']) ?></span>
                </div>
                <p><?= htmlspecialchars($offre['resume']) ?></p>
            </div>
        <?php endif; ?>

        <div class="modal-actions">
            <button class="btn-contact" onclick="window.location.href='tel:<?= htmlspecialchars($offre['contact']) ?>'">
                <i class="fas fa-phone"></i> Contacter
            </button>
            <button class="btn-secondary-modal" onclick="document.getElementById('modalClose').click()">
                Fermer
            </button>
        </div>
    </div>
</div>
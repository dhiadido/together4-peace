<?php
header('Content-Type: text/html; charset=utf-8');

require_once(__DIR__ . '/../../Controller/OffreController.php');

$score = isset($_GET['score']) ? intval($_GET['score']) : 0;
$categorie_probleme = isset($_GET['cat']) ? $_GET['cat'] : 'stress';

$controller = new OffreController();
$rows = $controller->showOffers($categorie_probleme, 12);

// Inclure le template
include "templateFront.php";
?>

<div class="offers-container" style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;">
    <h1 style="text-align: center; color: #2c3e50; margin-bottom: 30px; font-size: 2.5em;">
        Offres Recommandées pour Vous
    </h1>
    
    <?php if (!$rows || empty($rows)): ?>
        <div class="card" style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 12px;">
            <i class="fas fa-info-circle" style="font-size: 48px; color: #6c757d; margin-bottom: 20px;"></i>
            <p style="font-size: 1.2em; color: #6c757d;">Aucune offre trouvée pour cette catégorie.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; margin-top: 30px;">
            <?php foreach($rows as $r): ?>
                <?php
                $name = htmlspecialchars($r['nom_specialiste']);
                $desc = htmlspecialchars(strlen($r['description']) > 180 ? substr($r['description'], 0, 180) . '...' : $r['description']);
                $price = number_format($r['prix'], 2);
                $contact = htmlspecialchars($r['contact']);
                $id = intval($r['id_offre']);
                $image = !empty($r['image']) ? htmlspecialchars($r['image']) : null;
                ?>
                
                <div class="card" style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: transform 0.3s ease, box-shadow 0.3s ease; text-align: center;">
                    <?php if ($image && file_exists($_SERVER['DOCUMENT_ROOT'] . '/projet2/' . str_replace('../../', '', $image))): ?>
                        <img src="<?= $image ?>" 
                             alt="<?= $name ?>" 
                             style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 15px;">
                    <?php else: ?>
                        <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 64px;">
                            <i class="fas fa-user-md"></i>
                        </div>
                    <?php endif; ?>
                    
                    <h3 style="color: #2c3e50; margin: 15px 0; font-size: 1.5em;"><?= $name ?></h3>
                    <p style="color: #7f8c8d; margin: 10px 0; line-height: 1.6; min-height: 80px;"><?= $desc ?></p>
                    
                    <div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                        <p style="margin: 5px 0; color: #2c3e50;">
                            <strong style="color: #27ae60;">Prix:</strong> <?= $price ?> DT
                        </p>
                        <p style="margin: 5px 0; color: #2c3e50;">
                            <strong style="color: #3498db;">Contact:</strong> <?= $contact ?>
                        </p>
                    </div>
                    
                    <a href="mailto:<?= $contact ?>" style="display: inline-block; margin-top: 15px; text-decoration: none;">
                        <button style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 12px 30px; border-radius: 25px; font-size: 1em; cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease; font-weight: 600;">
                            <i class="fas fa-envelope"></i> Contacter
                        </button>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.2) !important;
}

.card button:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

@media (max-width: 768px) {
    .offers-container h1 {
        font-size: 1.8em !important;
    }
    
    .offers-container > div {
        grid-template-columns: 1fr !important;
    }
}
</style>

    </main>
</div>

</body>
</html>
<?php
// Fichier à inclure dans n'importe quelle page front
require_once(__DIR__ . '/../../Controller/RechercheController.php');

$rechercheController = new RechercheController();
$recherches = $rechercheController->getRandomRecherches(3); // 3 chercheurs aléatoires
?>

<div class="recherche-widget">
    <h3 class="widget-title">
        <i class="fa fa-graduation-cap"></i> Nos Chercheurs Experts
    </h3>
    
    <div class="recherche-cards">
        <?php foreach($recherches as $r): ?>
        <div class="recherche-card">
            <div class="recherche-image">
                <?php if (!empty($r['image'])): ?>
                    <img src="<?= htmlspecialchars($r['image']) ?>" alt="<?= htmlspecialchars($r['nom_chercheur']) ?>">
                <?php else: ?>
                    <div class="placeholder-img">
                        <i class="fa fa-user-graduate"></i>
                    </div>
                <?php endif; ?>
                
                <span class="disponibilite-badge <?= strtolower($r['disponibilite']) ?>">
                    <?= htmlspecialchars($r['disponibilite']) ?>
                </span>
            </div>
            
            <div class="recherche-content">
                <h4><?= htmlspecialchars($r['nom_chercheur']) ?></h4>
                <p class="domaine"><?= htmlspecialchars($r['domaine']) ?></p>
                <p class="institution"><i class="fa fa-university"></i> <?= htmlspecialchars($r['institution']) ?></p>
                <p class="description"><?= htmlspecialchars(substr($r['description'], 0, 100)) ?>...</p>
                
                <div class="recherche-actions">
                    <a href="ShowRecherche.php?id=<?= $r['id_recherche'] ?>" class="btn-view">
                        <i class="fa fa-eye"></i> Voir le profil
                    </a>
                    <a href="mailto:<?= htmlspecialchars($r['email']) ?>" class="btn-contact">
                        <i class="fa fa-envelope"></i> Contacter
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="widget-footer">
        <a href="Recherches.php" class="btn-view-all">
            Voir tous les chercheurs <i class="fa fa-arrow-right"></i>
        </a>
    </div>
</div>

<style>
.recherche-widget {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 12px;
    margin: 30px 0;
}

.widget-title {
    font-size: 24px;
    color: #2c3e50;
    margin-bottom: 25px;
    text-align: center;
}

.recherche-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.recherche-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.recherche-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.recherche-image {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.recherche-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-img {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 60px;
    color: white;
}

.disponibilite-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: white;
}

.disponibilite-badge.disponible {
    background: #28a745;
}

.disponibilite-badge.occupé {
    background: #dc3545;
}

.disponibilite-badge.sur.demande {
    background: #ffc107;
    color: #333;
}

.recherche-content {
    padding: 20px;
}

.recherche-content h4 {
    font-size: 18px;
    color: #2c3e50;
    margin-bottom: 8px;
}

.domaine {
    color: #667eea;
    font-weight: 600;
    margin-bottom: 8px;
}

.institution {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 12px;
}

.description {
    color: #555;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 15px;
}

.recherche-actions {
    display: flex;
    gap: 10px;
}

.btn-view, .btn-contact {
    flex: 1;
    padding: 10px;
    text-align: center;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-view {
    background: #667eea;
    color: white;
}

.btn-view:hover {
    background: #5568d3;
}

.btn-contact {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-contact:hover {
    background: #667eea;
    color: white;
}

.widget-footer {
    text-align: center;
    padding-top: 20px;
}

.btn-view-all {
    display: inline-block;
    padding: 12px 30px;
    background: #2c3e50;
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-view-all:hover {
    background: #1a252f;
    transform: translateX(5px);
}
</style>
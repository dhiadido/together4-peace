<<<<<<< HEAD
<?php
require_once(__DIR__ . '/../../Controller/ArticleController.php');

$controller = new ArticleController();

// 🔥 SMART RECOMMENDATION SYSTEM
$quizScore = isset($_GET['score']) ? (int)$_GET['score'] : 0;

if ($quizScore > 0) {
    // Use smart recommendations based on quiz score
    $articles = $controller->getSmartRecommendations($quizScore);
} else {
    // Fallback to regular list
    $articles = $controller->listArticlesWithOffres();
}

if (empty($articles)) {
    echo '<div class="no-articles">';
    echo '<h3>Aucun article disponible pour le moment</h3>';
    echo '<p>Revenez plus tard pour découvrir nos recommandations.</p>';
    echo '</div>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles Recommandés</title>
    <link rel="stylesheet" href="../../Public/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .recommendation-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        .badge-high {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .badge-medium {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .badge-popular {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        .badge-new {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .recommendation-score {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }
        .date-info {
            font-size: 12px;
            color: #999;
            margin-top: 3px;
        }
        .offre-relevance {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.95);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            color: #667eea;
        }
        .offre-new-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            animation: pulse 2s infinite;
        }
        .offre-card {
            position: relative;
        }
    </style>
</head>
<body>

<div class="articles-container">
    <div class="articles-header">
        <h2>🎯 Recommandations personnalisées pour vous</h2>
        <p>Basé sur votre score de <?= $quizScore ?>% - Voici les meilleures ressources</p>
    </div>

    <?php foreach ($articles as $index => $article): ?>
        <div class="article-card">
            <!-- Article Header -->
            <div class="article-header">
                <?php if (!empty($article['image'])): ?>
                    <img src="<?= htmlspecialchars($article['image']) ?>" 
                         alt="<?= htmlspecialchars($article['titre']) ?>" 
                         class="article-image">
                <?php endif; ?>
                <div class="article-info">
                    <div>
                        <span class="article-theme"><?= htmlspecialchars($article['theme']) ?></span>
                        
                        <?php 
                        // Show recommendation badges
                        $score = $article['recommendation_score'] ?? 0;
                        if ($score >= 80): ?>
                            <span class="recommendation-badge badge-high">🔥 Fortement Recommandé</span>
                        <?php elseif ($score >= 60): ?>
                            <span class="recommendation-badge badge-medium">⭐ Recommandé</span>
                        <?php endif; ?>
                        
                        <?php if (($article['avg_popularity'] ?? 0) >= 5): ?>
                            <span class="recommendation-badge badge-popular">👥 Populaire</span>
                        <?php endif; ?>
                        
                        <?php 
                        // 🆕 NEW BADGE for recent articles
                        $daysOld = $article['days_old'] ?? 999;
                        if ($daysOld <= 7): ?>
                            <span class="recommendation-badge badge-new">🆕 Nouveau</span>
                        <?php endif; ?>
                    </div>
                    
                    <h3><?= htmlspecialchars($article['titre']) ?></h3>
                    <p class="article-resume"><?= htmlspecialchars($article['resume']) ?></p>
                    
                    <?php if (isset($article['recommendation_score'])): ?>
                        <p class="recommendation-score">
                            📊 Score de pertinence: <?= number_format($article['recommendation_score'], 1) ?>/100
                            | <?= $article['offre_count'] ?> spécialiste(s) disponible(s)
                            <?php if ($article['min_price'] > 0): ?>
                                | À partir de <?= number_format($article['min_price'], 2) ?> DT
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php 
                    // 🆕 SHOW DATE
                    if (isset($article['days_old'])): ?>
                        <p class="date-info">
                            📅 Publié: <?= ArticleController::timeAgo($article['days_old']) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Article Content -->
            <div class="article-content">
                <p><?= nl2br(htmlspecialchars($article['contenu'])) ?></p>
            </div>

            <!-- Related Offres Section -->
            <?php if (!empty($article['offres'])): ?>
                <div class="offres-section">
                    <h4>🎯 Spécialistes recommandés pour ce sujet</h4>
                    <div class="offres-grid">
                        <?php foreach ($article['offres'] as $offreIndex => $offre): ?>
                            <div class="offre-card" onclick="showOfferModal(<?= $offre['id_offre'] ?>)">
                                <?php 
                                // 🆕 NEW OFFRE BADGE
                                $offreDaysOld = $offre['days_old'] ?? 999;
                                if ($offreDaysOld <= 7): ?>
                                    <div class="offre-new-badge">🆕 Nouveau</div>
                                <?php endif; ?>
                                
                                <?php if ($offreIndex === 0 && $quizScore < 40): ?>
                                    <div class="offre-relevance">✨ Top Match</div>
                                <?php endif; ?>
                                
                                <div class="offre-image-container">
                                    <?php if (!empty($offre['image'])): ?>
                                        <img src="<?= htmlspecialchars($offre['image']) ?>" 
                                             alt="<?= htmlspecialchars($offre['nom_specialiste']) ?>"
                                             class="offre-image">
                                    <?php endif; ?>
                                    <span class="offre-category"><?= htmlspecialchars($offre['categorie']) ?></span>
                                </div>
                                <div class="offre-info">
                                    <h5><?= htmlspecialchars($offre['nom_specialiste']) ?></h5>
                                    <p class="offre-description"><?= htmlspecialchars(substr($offre['description'], 0, 80)) ?>...</p>
                                    <div class="offre-footer">
                                        <span class="offre-price"><?= number_format($offre['prix'], 2) ?> DT</span>
                                        <span class="offre-link">Voir détails →</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-top: 8px;">
                                        <?php if (($offre['popularite'] ?? 0) >= 5): ?>
                                        <small style="color: #667eea;">⭐ Populaire (<?= $offre['popularite'] ?> vues)</small>
                                    <?php endif; ?>
                                    <?php if (isset($offre['days_old'])): ?>
                                        <small style="color: #999;">📅 <?= ArticleController::timeAgo($offre['days_old']) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="no-offres">
                <p>Aucune offre spécialisée pour cet article actuellement.</p>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>

<script src="../../assets/js/scriptArticles.js"></script>
</body>
=======
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Together4Peace - Bâtir des Ponts. Non des Murs.</title>
    <link rel="stylesheet" href="..\..\assets\css\styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="offers-container" align="center">
    <h1>Articles Recommandées pour Vous</h1>
</body>
</html>

<?php
require_once(__DIR__ . '/../../Controller/ArticleController.php');
$controller = new ArticleController();

// If an article ID is provided → show that article
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $article = $controller->getArticleById($id);

    ?>

    <h2><?= htmlspecialchars($article['titre']) ?></h2>
    <p><strong>Thème :</strong> <?= htmlspecialchars($article['theme']) ?></p>

    <p><?= nl2br(htmlspecialchars($article['contenu'])) ?></p>

    <?php if (!empty($article['image'])): ?>
        <img src="../../assets/images/<?= htmlspecialchars($article['image']) ?>" width="300" alt="<?= htmlspecialchars($article['titre']) ?>">
    <?php endif; ?>

    <br><br>
    <a href="Articles.php" class="btn btn-secondary">Retour à la liste des articles</a>

    </div>
    </body>
    </html>
    <?php
    exit;
}

$data = $controller->listArticles();

?>

<?php if (empty($data)): ?>
    <p>Aucun article disponible pour le moment.</p>
<?php else: ?>
    <?php foreach ($data as $a): ?>
        <div class="card" style="padding:15px;margin-bottom:15px;border:1px solid #ccc;border-radius:8px;">
            <h3><?= htmlspecialchars($a['titre']) ?></h3>
            <p><strong>Thème :</strong> <?= htmlspecialchars($a['theme']) ?></p>
            <p><?= htmlspecialchars($a['resume']) ?></p><br>
            <a href="Articles.php?id=<?= $a['id_article'] ?>" class="btn btn-primary">Lire plus</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</div>
</body>
>>>>>>> 70a1b443d163ee0a60357ea7d5e6588e414d81f3
</html>
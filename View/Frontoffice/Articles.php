<?php
require_once(__DIR__ . '/../../Controller/ArticleController.php');

$controller = new ArticleController();

$quizScore = isset($_GET['score']) ? (int)$_GET['score'] : 0;

if ($quizScore > 0) {
    $articles = $controller->getSmartRecommendations($quizScore);
} else {
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
    <title>Articles Recommandés - Together4Peace</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
            padding: 40px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 2.5em;
            color: #2c3e50;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header p {
            font-size: 1.2em;
            color: #7f8c8d;
        }

        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .article-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .article-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        }

        .article-image-wrapper {
            width: 100%;
            height: 250px;
            position: relative;
            overflow: hidden;
        }

        .article-image, .no-article-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-article-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 64px;
        }

        .badges-overlay {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .badge-high {
            background: rgba(255, 59, 48, 0.95);
            color: white;
        }

        .badge-medium {
            background: rgba(255, 149, 0, 0.95);
            color: white;
        }

        .badge-popular {
            background: rgba(52, 199, 89, 0.95);
            color: white;
        }

        .badge-new {
            background: rgba(255, 204, 0, 0.95);
            color: #000;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .article-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .article-theme {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .article-title {
            font-size: 1.5em;
            color: #2c3e50;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .article-resume {
            color: #7f8c8d;
            line-height: 1.6;
            margin-bottom: 15px;
            flex: 1;
        }

        .article-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            padding-top: 15px;
            border-top: 2px solid #ecf0f1;
            font-size: 13px;
            color: #95a5a6;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .article-full-content {
            background: #f8f9fa;
            padding: 20px;
            margin: 20px 0;
            border-radius: 12px;
            border-left: 4px solid #667eea;
            line-height: 1.8;
            color: #34495e;
        }

        .offres-section {
            padding: 25px;
            background: #f8f9fa;
            border-top: 3px solid #667eea;
        }

        .offres-section h4 {
            font-size: 1.3em;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .offres-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .offre-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .offre-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .offre-image-container {
            position: relative;
            height: 180px;
            overflow: hidden;
        }

        .offre-image, .no-offre-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-offre-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
        }

        .offre-category {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(255, 255, 255, 0.95);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            color: #667eea;
        }

        .offre-badge {
            position: absolute;
            top: 10px;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
            backdrop-filter: blur(10px);
        }

        .offre-new-badge {
            left: 10px;
            background: rgba(255, 204, 0, 0.95);
            color: #000;
            animation: pulse 2s infinite;
        }

        .offre-top-badge {
            right: 10px;
            background: rgba(255, 59, 48, 0.95);
            color: white;
        }

        .offre-info {
            padding: 15px;
        }

        .offre-name {
            font-size: 1.1em;
            color: #2c3e50;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .offre-description {
            color: #7f8c8d;
            font-size: 0.9em;
            line-height: 1.5;
            margin-bottom: 12px;
            min-height: 60px;
        }

        .offre-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid #ecf0f1;
        }

        .offre-price {
            font-size: 1.2em;
            font-weight: bold;
            color: #27ae60;
        }

        .offre-link {
            color: #667eea;
            font-size: 0.9em;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .offre-card:hover .offre-link {
            color: #764ba2;
            transform: translateX(5px);
        }

        .offre-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 11px;
            color: #95a5a6;
        }

        .no-offres {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 15px;
            color: #7f8c8d;
        }

        @media (max-width: 768px) {
            .articles-grid {
                grid-template-columns: 1fr;
            }

            .offres-grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 1.8em;
            }

            .header p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🎯 Articles Recommandés Pour Vous</h1>
        <p>Score: <?= $quizScore ?>% - <?= count($articles) ?> article(s) personnalisé(s)</p>
    </div>

    <div class="articles-grid">
        <?php foreach ($articles as $article): ?>
            <div class="article-card">
                <div class="article-image-wrapper">
                    <?php if (!empty($article['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/projet2/' . str_replace('../../', '', $article['image']))): ?>
                        <img src="<?= htmlspecialchars($article['image']) ?>" 
                             alt="<?= htmlspecialchars($article['titre']) ?>" 
                             class="article-image">
                    <?php else: ?>
                        <div class="no-article-image">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="badges-overlay">
                        <?php 
                        $score = $article['recommendation_score'] ?? 0;
                        if ($score >= 80): ?>
                            <span class="badge badge-high">🔥 Top Recommandé</span>
                        <?php elseif ($score >= 60): ?>
                            <span class="badge badge-medium">⭐ Recommandé</span>
                        <?php endif; ?>
                        
                        <?php if (($article['avg_popularity'] ?? 0) >= 5): ?>
                            <span class="badge badge-popular">👥 Populaire</span>
                        <?php endif; ?>
                        
                        <?php 
                        $daysOld = $article['days_old'] ?? 999;
                        if ($daysOld <= 7): ?>
                            <span class="badge badge-new">🆕 Nouveau</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="article-content">
                    <span class="article-theme"><?= htmlspecialchars($article['theme']) ?></span>
                    
                    <h3 class="article-title"><?= htmlspecialchars($article['titre']) ?></h3>
                    
                    <p class="article-resume"><?= htmlspecialchars($article['resume']) ?></p>
                    
                    <div class="article-meta">
                        <?php if (isset($article['recommendation_score'])): ?>
                            <span class="meta-item">
                                <i class="fas fa-chart-line"></i>
                                Score: <?= number_format($article['recommendation_score'], 1) ?>/100
                            </span>
                        <?php endif; ?>
                        
                        <?php if (isset($article['offre_count']) && $article['offre_count'] > 0): ?>
                            <span class="meta-item">
                                <i class="fas fa-user-md"></i>
                                <?= $article['offre_count'] ?> spécialiste(s)
                            </span>
                        <?php endif; ?>
                        
                        <?php if (isset($article['min_price']) && $article['min_price'] > 0): ?>
                            <span class="meta-item">
                                <i class="fas fa-tag"></i>
                                À partir de <?= number_format($article['min_price'], 2) ?> DT
                            </span>
                        <?php endif; ?>
                        
                        <?php if (isset($article['days_old'])): ?>
                            <span class="meta-item">
                                <i class="fas fa-clock"></i>
                                <?= ArticleController::timeAgo($article['days_old']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($article['contenu'])): ?>
                    <div class="article-full-content">
                        <?= nl2br(htmlspecialchars($article['contenu'])) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($article['offres'])): ?>
                    <div class="offres-section">
                        <h4>
                            <i class="fas fa-user-md"></i>
                            Spécialistes Disponibles
                        </h4>
                        <div class="offres-grid">
                            <?php foreach ($article['offres'] as $offreIndex => $offre): ?>
                                <div class="offre-card" onclick="window.location.href='Offres.php?id=<?= $offre['id_offre'] ?>'">
                                    <div class="offre-image-container">
                                        <?php if (!empty($offre['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/projet2/' . str_replace('../../', '', $offre['image']))): ?>
                                            <img src="<?= htmlspecialchars($offre['image']) ?>" 
                                                 alt="<?= htmlspecialchars($offre['nom_specialiste']) ?>"
                                                 class="offre-image">
                                        <?php else: ?>
                                            <div class="no-offre-image">
                                                <i class="fas fa-user-md"></i>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php 
                                        $offreDaysOld = $offre['days_old'] ?? 999;
                                        if ($offreDaysOld <= 7): ?>
                                            <div class="offre-badge offre-new-badge">🆕 Nouveau</div>
                                        <?php endif; ?>
                                        
                                        <?php if ($offreIndex === 0 && $quizScore < 40): ?>
                                            <div class="offre-badge offre-top-badge">✨ Top Match</div>
                                        <?php endif; ?>
                                        
                                        <span class="offre-category"><?= htmlspecialchars($offre['categorie']) ?></span>
                                    </div>
                                    
                                    <div class="offre-info">
                                        <h5 class="offre-name"><?= htmlspecialchars($offre['nom_specialiste']) ?></h5>
                                        <p class="offre-description"><?= htmlspecialchars(substr($offre['description'], 0, 100)) ?>...</p>
                                        
                                        <div class="offre-footer">
                                            <span class="offre-price"><?= number_format($offre['prix'], 2) ?> DT</span>
                                            <span class="offre-link">Voir détails →</span>
                                        </div>
                                        
                                        <div class="offre-stats">
                                            <?php if (($offre['popularite'] ?? 0) >= 5): ?>
                                                <span>⭐ <?= $offre['popularite'] ?> vues</span>
                                            <?php endif; ?>
                                            <?php if (isset($offre['days_old'])): ?>
                                                <span>📅 <?= ArticleController::timeAgo($offre['days_old']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="offres-section">
                        <div class="no-offres">
                            <i class="fas fa-info-circle" style="font-size: 48px; color: #95a5a6; margin-bottom: 15px;"></i>
                            <p>Aucun spécialiste disponible pour cet article actuellement.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
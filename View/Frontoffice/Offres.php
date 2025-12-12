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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($offre['nom_specialiste']) ?> - Together4Peace</title>
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
            max-width: 1000px;
            margin: 0 auto;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: white;
            color: #667eea;
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            background: #667eea;
            color: white;
        }

        .offre-detail-card {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            margin-bottom: 30px;
        }

        .offre-header {
            position: relative;
            padding: 50px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .offre-image-section {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 30px;
        }

        .offre-image-wrapper {
            flex-shrink: 0;
        }

        .offre-image {
            width: 200px;
            height: 200px;
            border-radius: 20px;
            object-fit: cover;
            border: 5px solid rgba(255,255,255,0.3);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .no-offre-image {
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            border: 5px solid rgba(255,255,255,0.3);
            backdrop-filter: blur(10px);
        }

        .offre-title-section {
            flex: 1;
        }

        .offre-name {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 15px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .offre-category {
            display: inline-block;
            background: rgba(255,255,255,0.25);
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.3);
        }

        .offre-price-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 25px 40px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
        }

        .price-tag {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .price-label {
            font-size: 1em;
            opacity: 0.9;
        }

        .price-value {
            font-size: 2.5em;
            font-weight: bold;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .contact-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: white;
            color: #667eea;
            padding: 15px 35px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1em;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }

        .contact-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .offre-body {
            padding: 40px;
        }

        .section {
            margin-bottom: 35px;
        }

        .section-title {
            font-size: 1.5em;
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 12px;
            border-bottom: 3px solid #667eea;
        }

        .section-title i {
            color: #667eea;
            font-size: 1.2em;
        }

        .description-text {
            color: #34495e;
            line-height: 1.8;
            font-size: 1.1em;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            border-left: 4px solid #667eea;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .info-icon {
            flex-shrink: 0;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5em;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-weight: 600;
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-value {
            color: #2c3e50;
            font-size: 1.1em;
            font-weight: 500;
        }

        .article-section {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            padding: 30px;
            border-radius: 20px;
            border: 2px solid #667eea30;
        }

        .article-badge {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .article-title {
            font-size: 1.3em;
            color: #2c3e50;
            font-weight: bold;
        }

        .article-theme-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
        }

        .article-resume {
            color: #34495e;
            line-height: 1.7;
            font-size: 1em;
            margin-top: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #ecf0f1;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            font-size: 1em;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(56, 239, 125, 0.4);
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(56, 239, 125, 0.6);
        }

        @media (max-width: 768px) {
            .offre-image-section {
                flex-direction: column;
                text-align: center;
            }

            .offre-name {
                font-size: 1.8em;
            }

            .offre-price-section {
                flex-direction: column;
                gap: 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .offre-body {
                padding: 20px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <a href="javascript:history.back()" class="back-button">
        <i class="fas fa-arrow-left"></i>
        Retour aux articles
    </a>

    <div class="offre-detail-card">
        <div class="offre-header">
            <div class="offre-image-section">
                <div class="offre-image-wrapper">
                    <?php if (!empty($offre['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/projet2/' . str_replace('../../', '', $offre['image']))): ?>
                        <img src="<?= htmlspecialchars($offre['image']) ?>" 
                             alt="<?= htmlspecialchars($offre['nom_specialiste']) ?>"
                             class="offre-image">
                    <?php else: ?>
                        <div class="no-offre-image">
                            <i class="fas fa-user-md"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="offre-title-section">
                    <h1 class="offre-name"><?= htmlspecialchars($offre['nom_specialiste']) ?></h1>
                    <span class="offre-category">
                        <i class="fas fa-briefcase"></i>
                        <?= htmlspecialchars($offre['categorie']) ?>
                    </span>
                </div>
            </div>

            <div class="offre-price-section">
                <div class="price-tag">
                    <span class="price-label">Tarif de consultation :</span>
                    <span class="price-value"><?= number_format($offre['prix'], 2) ?> DT</span>
                </div>
                <a href="tel:<?= htmlspecialchars($offre['contact']) ?>" class="contact-button">
                    <i class="fas fa-phone-alt"></i>
                    Contacter maintenant
                </a>
            </div>
        </div>

        <div class="offre-body">
            <div class="section">
                <h2 class="section-title">
                    <i class="fas fa-file-alt"></i>
                    Description
                </h2>
                <div class="description-text">
                    <?= nl2br(htmlspecialchars($offre['description'])) ?>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Informations
                </h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Spécialité</div>
                            <div class="info-value"><?= htmlspecialchars($offre['categorie_probleme']) ?></div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Contact</div>
                            <div class="info-value"><?= htmlspecialchars($offre['contact']) ?></div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Tarif</div>
                            <div class="info-value"><?= number_format($offre['prix'], 2) ?> DT</div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Disponibilité</div>
                            <div class="info-value">Sur rendez-vous</div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($offre['titre'])): ?>
                <div class="section">
                    <h2 class="section-title">
                        <i class="fas fa-newspaper"></i>
                        Article Associé
                    </h2>
                    <div class="article-section">
                        <div class="article-badge">
                            <span class="article-title"><?= htmlspecialchars($offre['titre']) ?></span>
                            <span class="article-theme-badge"><?= htmlspecialchars($offre['theme']) ?></span>
                        </div>
                        <p class="article-resume"><?= htmlspecialchars($offre['resume']) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="action-buttons">
                <a href="tel:<?= htmlspecialchars($offre['contact']) ?>" class="btn btn-primary">
                    <i class="fas fa-phone-alt"></i>
                    Appeler maintenant
                </a>
                <a href="mailto:<?= htmlspecialchars($offre['contact']) ?>" class="btn btn-success">
                    <i class="fas fa-envelope"></i>
                    Envoyer un email
                </a>
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
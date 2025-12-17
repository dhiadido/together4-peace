<?php
require_once(__DIR__ . '/../../Controller/RechercheController.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$controller = new RechercheController();
$r = $controller->getRechercheById($id);

if (!$r) {
    header('Location: Recherches.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($r['nom_chercheur']) ?> - Profil Chercheur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            color: white;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            margin: -30px auto 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .profile-top {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
            align-items: flex-start;
        }

        .profile-image {
            flex-shrink: 0;
        }

        .profile-image img {
            width: 200px;
            height: 200px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .profile-placeholder {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            color: white;
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-size: 32px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .profile-domaine {
            font-size: 20px;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .profile-institution {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .profile-specialite {
            display: inline-block;
            background: #f0f0f0;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }

        .profile-status {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .status-disponible {
            background: #d4edda;
            color: #155724;
        }

        .status-occupé {
            background: #f8d7da;
            color: #721c24;
        }

        .status-demande {
            background: #fff3cd;
            color: #856404;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 22px;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .description {
            line-height: 1.8;
            color: #555;
            font-size: 16px;
        }

        .publications {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            white-space: pre-line;
            line-height: 1.8;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .contact-item i {
            font-size: 20px;
            color: #667eea;
        }

        .contact-item a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .cv-download {
            display: inline-block;
            padding: 15px 30px;
            background: #667eea;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .cv-download:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .back-button {
            display: inline-block;
            padding: 12px 24px;
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .back-button:hover {
            background: #667eea;
            color: white;
        }
    </style>
</head>
<body>

<div class="profile-header">
    <div class="container">
        <a href="Recherches.php" class="back-button">
            <i class="fa fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
</div>

<div class="container">
    <div class="profile-card">
        <div class="profile-top">
            <div class="profile-image">
                <?php if (!empty($r['image'])): ?>
                    <img src="<?= htmlspecialchars($r['image']) ?>" alt="<?= htmlspecialchars($r['nom_chercheur']) ?>">
                <?php else: ?>
                    <div class="profile-placeholder">
                        <i class="fa fa-user-graduate"></i>
                    </div>
                <?php endif; ?>
            </div>

            <div class="profile-info">
                <h1 class="profile-name"><?= htmlspecialchars($r['nom_chercheur']) ?></h1>
                <p class="profile-domaine"><i class="fa fa-microscope"></i> <?= htmlspecialchars($r['domaine']) ?></p>
                <p class="profile-institution">
                    <i class="fa fa-university"></i> <?= htmlspecialchars($r['institution']) ?>
                </p>

                <?php if (!empty($r['specialite'])): ?>
                    <span class="profile-specialite">
                        <i class="fa fa-star"></i> <?= htmlspecialchars($r['specialite']) ?>
                    </span>
                <?php endif; ?>

                <?php if (!empty($r['disponibilite'])): ?>
                    <span class="profile-status status-<?= strtolower($r['disponibilite']) ?>">
                        <?= htmlspecialchars($r['disponibilite']) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($r['description'])): ?>
            <div class="section">
                <h2 class="section-title">
                    <i class="fa fa-info-circle"></i> À propos
                </h2>
                <p class="description">
                    <?= htmlspecialchars($r['description']) ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if (!empty($r['publications'])): ?>
            <div class="section">
                <h2 class="section-title">
                    <i class="fa fa-book"></i> Publications
                </h2>
                <div class="publications">
                    <?= htmlspecialchars($r['publications']) ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="section">
            <h2 class="section-title">
                <i class="fa fa-address-book"></i> Informations de contact
            </h2>
            <div class="contact-info">
                <?php if (!empty($r['email'])): ?>
                    <div class="contact-item">
                        <i class="fa fa-envelope"></i>
                        <a href="mailto:<?= htmlspecialchars($r['email']) ?>">
                            <?= htmlspecialchars($r['email']) ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($r['telephone'])): ?>
                    <div class="contact-item">
                        <i class="fa fa-phone"></i>
                        <span>
                            <?= htmlspecialchars($r['telephone']) ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($r['institution'])): ?>
                    <div class="contact-item">
                        <i class="fa fa-university"></i>
                        <span>
                            <?= htmlspecialchars($r['institution']) ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($r['cv'])): ?>
            <div class="section">
                <a href="<?= htmlspecialchars($r['cv']) ?>" class="cv-download" target="_blank">
                    <i class="fa fa-download"></i> Télécharger le CV
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
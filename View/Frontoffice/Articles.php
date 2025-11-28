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
</html>
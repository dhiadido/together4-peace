<?php
require_once(__DIR__ . '/../../Controller/ArticleController.php');
require_once(__DIR__ . '/../../Model/Article.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$controller = new ArticleController();

// --- Récupération de l'article existant ---
$article = $controller->getArticleById($id);

// --- Traitement du formulaire ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Gestion de l'image
    $imagePath = $article['image'];
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = "uploads/articles/";
        $imagePath = $uploadDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "../../" . $imagePath);
    }

    // Créer l'objet Article avec les données modifiées
    $articleObj = new Article(
        $_POST['titre'],
        $_POST['theme'],
        $_POST['resume'],
        $_POST['contenu'],
        $imagePath
    );

    // Update en BD
    $controller->updateArticle($articleObj, $id);

    header("Location: ArticleList.php");
    exit;
}

include "template.php";
?>

<h2 class="mb-4">Modifier un article</h2>

<form method="POST" enctype="multipart/form-data" class="card p-4 shadow">

    <div class="mb-3">
        <label class="form-label">Titre</label>
        <input type="text" name="titre" class="form-control" 
               value="<?= htmlspecialchars($article['titre']) ?>" 
               placeholder="Ex: Les bienfaits de la méditation pour réduire le stress" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Thème</label>
        <input type="text" name="theme" class="form-control" 
               value="<?= htmlspecialchars($article['theme']) ?>" 
               placeholder="Ex: Santé mentale, Bien-être, Psychologie..." required>
    </div>

    <div class="mb-3">
        <label class="form-label">Résumé</label>
        <textarea name="resume" rows="5" class="form-control" 
                  placeholder="Écrivez un bref résumé de l'article (2-3 phrases)"><?= htmlspecialchars($article['resume']) ?></textarea>
        <small class="text-muted">Court résumé pour attirer l'attention du lecteur</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Contenu</label>
        <textarea name="contenu" rows="15" class="form-control" 
                  placeholder="Écrivez le contenu complet de votre article ici..."><?= htmlspecialchars($article['contenu']) ?></textarea>
        <small class="text-muted">Contenu détaillé de l'article</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Image actuelle</label>
        <?php if (!empty($article['image'])): ?>
            <div class="mb-2">
                <img src="../../assets/images/<?= htmlspecialchars($article['image']) ?>" 
                     style="width:200px;height:150px;object-fit:cover;border-radius:8px" 
                     alt="Image actuelle">
            </div>
        <?php endif; ?>
        
        <label class="form-label">Changer l'image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        <small class="text-muted">Formats acceptés : JPG, PNG, GIF, WEBP (laisser vide pour garder l'image actuelle)</small>
    </div>

    <button type="submit" name="submit" class="btn btn-warning">Enregistrer les modifications</button>
    <a href="ArticleList.php" class="btn btn-secondary">Annuler</a>
    

</form>

    <script src="../../assets/js/validationArticle.js"></script>
    
</div>
</body>
</html>
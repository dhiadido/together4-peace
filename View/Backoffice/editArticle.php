<?php
require_once(__DIR__ . '/../../Controller/ArticleController.php');
require_once(__DIR__ . '/../../Model/Article.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$controller = new ArticleController();

$article = $controller->getArticleById($id);

if (!$article) {
    header('Location: ArticleList.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $imagePath = $article['image']; // Garder l'image actuelle par défaut
    
    // Si une nouvelle image est uploadée
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
        // Supprimer l'ancienne image si elle existe
        if (!empty($article['image'])) {
            $oldImagePath = $_SERVER['DOCUMENT_ROOT'] . '/projet2/' . str_replace('../../', '', $article['image']);
            if (file_exists($oldImagePath) && strpos($oldImagePath, 'images/articles/') !== false) {
                unlink($oldImagePath);
            }
        }
        $imagePath = $controller->handleImageUpload();
    }

    $articleObj = new Article(
        $_POST['titre'],
        $_POST['theme'],
        $_POST['resume'],
        $_POST['contenu'],
        $imagePath
    );

    $controller->updateArticle($articleObj, $id);

    header("Location: ArticleList.php");
    exit;
}

include "template.php";
?>

<h1>Modifier un article</h1>

<div class="form-container">
    <form method="POST" enctype="multipart/form-data" class="admin-form">

        <div class="form-group">
            <label class="form-label">Titre de l'article *</label>
            <input type="text" name="titre" class="form-input" 
                   value="<?= htmlspecialchars($article['titre']) ?>" 
                   placeholder="Ex: Les bienfaits de la méditation pour réduire le stress">
        </div>

        <div class="form-group">
            <label class="form-label">Thème *</label>
            <input type="text" name="theme" class="form-input" 
                   value="<?= htmlspecialchars($article['theme']) ?>" 
                   placeholder="Ex: Santé mentale, Bien-être, Psychologie...">
        </div>

        <div class="form-group">
            <label class="form-label">Résumé</label>
            <textarea name="resume" rows="4" class="form-input" 
                      placeholder="Écrivez un bref résumé de l'article (2-3 phrases)"><?= htmlspecialchars($article['resume']) ?></textarea>
            <small class="form-hint">Court résumé pour attirer l'attention du lecteur</small>
        </div>

        <div class="form-group">
            <label class="form-label">Contenu complet</label>
            <textarea name="contenu" rows="12" class="form-input" 
                      placeholder="Écrivez le contenu complet de votre article ici..."><?= htmlspecialchars($article['contenu']) ?></textarea>
            <small class="form-hint">Contenu détaillé de l'article</small>
        </div>

        <div class="form-group">
            <?php if (!empty($article['image'])): ?>
                <label class="form-label">Image actuelle</label>
                <div class="current-image" style="margin: 15px 0;">
                    <img src="<?= htmlspecialchars($article['image']) ?>" 
                         alt="Image actuelle"
                         style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                </div>
            <?php else: ?>
                <p style="color: #666; font-style: italic;">Aucune image actuellement</p>
            <?php endif; ?>
            
            <label class="form-label" style="margin-top: 20px;">Changer l'image (optionnel)</label>
            <input type="file" name="image" id="imageInput" class="form-input-file" accept="image/*">
            <small class="form-hint">Formats acceptés : JPG, PNG, GIF, WEBP (laisser vide pour garder l'image actuelle)</small>
            
            <div class="image-preview" id="imagePreview" style="margin-top: 15px;"></div>
        </div>

        <div class="form-actions">
            <button type="submit" name="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Enregistrer les modifications
            </button>
            <a href="ArticleList.php" class="btn btn-secondary">
                <i class="fa fa-times"></i> Annuler
            </a>
        </div>

    </form>
</div>

<script src="../../assets/js/validationArticle.js"></script>

    </main>
</div>

</body>
</html>
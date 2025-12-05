<?php
require_once(__DIR__ . '/../../Controller/ArticleController.php');
require_once(__DIR__ . '/../../Model/Article.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$controller = new ArticleController();

// Récupération de l'article existant
$article = $controller->getArticleById($id);

if (!$article) {
    header('Location: ArticleList.php');
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Gestion de l'image : garder l'ancienne si aucune nouvelle n'est uploadée
    $imagePath = $article['image'];
    
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
        $imagePath = $controller->handleImageUpload();
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

<h1>Modifier un article</h1>

<div class="form-container">
    <form method="POST" enctype="multipart/form-data" class="admin-form">

        <div class="form-group">
            <label class="form-label">Titre de l'article</label>
            <input type="text" name="titre" class="form-input" 
                   value="<?= htmlspecialchars($article['titre']) ?>" 
                   placeholder="Ex: Les bienfaits de la méditation pour réduire le stress" required>
        </div>

        <div class="form-group">
            <label class="form-label">Thème</label>
            <input type="text" name="theme" class="form-input" 
                   value="<?= htmlspecialchars($article['theme']) ?>" 
                   placeholder="Ex: Santé mentale, Bien-être, Psychologie..." required>
        </div>

        <div class="form-group">
            <label class="form-label">Résumé</label>
            <textarea name="resume" rows="4" class="form-input" 
                      placeholder="Écrivez un bref résumé de l'article (2-3 phrases)" required><?= htmlspecialchars($article['resume']) ?></textarea>
            <small class="form-hint">Court résumé pour attirer l'attention du lecteur</small>
        </div>

        <div class="form-group">
            <label class="form-label">Contenu complet</label>
            <textarea name="contenu" rows="12" class="form-input" 
                      placeholder="Écrivez le contenu complet de votre article ici..." required><?= htmlspecialchars($article['contenu']) ?></textarea>
            <small class="form-hint">Contenu détaillé de l'article</small>
        </div>

        <div class="form-group">
            <label class="form-label">Image actuelle</label>
            <?php if (!empty($article['image'])): ?>
                <div class="current-image" style="margin: 15px 0;">
                    <img src="<?= htmlspecialchars($article['image']) ?>" 
                         alt="Image actuelle"
                         style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                </div>
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
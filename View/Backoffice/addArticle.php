<?php
require_once(__DIR__ . '/../../Controller/ArticleController.php');
require_once(__DIR__ . '/../../Model/Article.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $controller = new ArticleController();

    $imagePath = $controller->handleImageUpload();

    $article = new Article(
        $_POST['titre'],
        $_POST['theme'],
        $_POST['resume'],
        $_POST['contenu'],
        $imagePath  // Peut être NULL maintenant
    );

    $controller->addArticle($article);

    header('Location: ArticleList.php');
    exit;
}

include "template.php";
?>

<h1>Ajouter un article</h1>

<div class="form-container">
    <form method="POST" enctype="multipart/form-data" class="admin-form">

        <div class="form-group">
            <label class="form-label">Titre de l'article *</label>
            <input type="text" name="titre" class="form-input" 
                   placeholder="Ex: Les bienfaits de la méditation pour réduire le stress">
        </div>

        <div class="form-group">
            <label class="form-label">Thème *</label>
            <input type="text" name="theme" class="form-input" 
                   placeholder="Ex: Santé mentale, Bien-être, Psychologie...">
        </div>

        <div class="form-group">
            <label class="form-label">Résumé</label>
            <textarea name="resume" rows="4" class="form-input" 
                      placeholder="Écrivez un bref résumé de l'article (2-3 phrases). Ce résumé apparaîtra dans la liste des articles."></textarea>
            <small class="form-hint">Court résumé pour attirer l'attention du lecteur (optionnel)</small>
        </div>

        <div class="form-group">
            <label class="form-label">Contenu complet</label>
            <textarea name="contenu" rows="12" class="form-input" 
                      placeholder="Écrivez le contenu complet de votre article ici. Vous pouvez développer en détail le sujet, ajouter des paragraphes, des conseils pratiques, des exemples concrets..."></textarea>
            <small class="form-hint">Contenu détaillé de l'article (optionnel)</small>
        </div>

        <div class="form-group">
            <label class="form-label">Image d'illustration (optionnel)</label>
            <input type="file" name="image" id="imageInput" class="form-input-file" accept="image/*">
            <small class="form-hint">Formats acceptés : JPG, PNG, GIF, WEBP. Aucune image par défaut si laissé vide.</small>
            
            <div class="image-preview" id="imagePreview" style="margin-top: 15px;"></div>
        </div>

        <div class="form-actions">
            <button type="submit" name="submit" class="btn btn-primary">
                <i class="fa fa-check"></i> Ajouter l'article
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
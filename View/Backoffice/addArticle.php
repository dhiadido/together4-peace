<?php
require_once(__DIR__ . '/../../Controller/ArticleController.php');
require_once(__DIR__ . '/../../Model/Article.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $controller = new ArticleController();

    // Image upload
    $imagePath = $controller->handleImageUpload();

    // Create Article object
    $article = new Article(
        $_POST['titre'],
        $_POST['theme'],
        $_POST['resume'],
        $_POST['contenu'],
        $imagePath
    );

    // Save to DB
    $controller->addArticle($article);

    header('Location: ArticleList.php');
    exit;
}

include "template.php";
?>

<h2 class="mb-4">Ajouter un article</h2>

<form method="POST" enctype="multipart/form-data" class="card p-4 shadow">

    <div class="mb-3">
        <label class="form-label">Titre</label>
        <input type="text" name="titre" class="form-control" 
               placeholder="Ex: Les bienfaits de la méditation pour réduire le stress" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Thème</label>
        <input type="text" name="theme" class="form-control" 
               placeholder="Ex: Santé mentale, Bien-être, Psychologie..." required>
    </div>

    <div class="mb-3">
        <label class="form-label">Résumé</label>
        <textarea name="resume" rows="5" class="form-control" 
                  placeholder="Écrivez un bref résumé de l'article (2-3 phrases). Ce résumé apparaîtra dans la liste des articles."></textarea>
        <small class="text-muted">Court résumé pour attirer l'attention du lecteur</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Contenu</label>
        <textarea name="contenu" rows="13" class="form-control" 
                  placeholder="Écrivez le contenu complet de votre article ici. Vous pouvez développer en détail le sujet, ajouter des paragraphes, des conseils pratiques, des exemples concrets..."></textarea>
        <small class="text-muted">Contenu détaillé de l'article</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        <small class="text-muted">Formats acceptés : JPG, PNG, GIF, WEBP (optionnel)</small>
    </div>

    <button type="submit" name="submit" class="btn btn-success">Ajouter l'article</button><br>
    <a href="ArticleList.php" class="btn btn-secondary">Annuler</a>

    <script src="../../assets/js/validationArticle.js"></script>

</form>

</div>
</body>
</html>
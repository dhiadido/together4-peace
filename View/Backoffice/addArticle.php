<<<<<<< HEAD
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

<h1>Ajouter un article</h1>

<div class="form-container">
    <form method="POST" enctype="multipart/form-data" class="admin-form">

        <div class="form-group">
            <label class="form-label">Titre de l'article</label>
            <input type="text" name="titre" class="form-input" 
                   placeholder="Ex: Les bienfaits de la méditation pour réduire le stress" required>
        </div>

        <div class="form-group">
            <label class="form-label">Thème</label>
            <input type="text" name="theme" class="form-input" 
                   placeholder="Ex: Santé mentale, Bien-être, Psychologie..." required>
        </div>

        <div class="form-group">
            <label class="form-label">Résumé</label>
            <textarea name="resume" rows="4" class="form-input" 
                      placeholder="Écrivez un bref résumé de l'article (2-3 phrases). Ce résumé apparaîtra dans la liste des articles." required></textarea>
            <small class="form-hint">Court résumé pour attirer l'attention du lecteur</small>
        </div>

        <div class="form-group">
            <label class="form-label">Contenu complet</label>
            <textarea name="contenu" rows="12" class="form-input" 
                      placeholder="Écrivez le contenu complet de votre article ici. Vous pouvez développer en détail le sujet, ajouter des paragraphes, des conseils pratiques, des exemples concrets..." required></textarea>
            <small class="form-hint">Contenu détaillé de l'article</small>
        </div>

        <div class="form-group">
            <label class="form-label">Image d'illustration</label>
            <input type="file" name="image" id="imageInput" class="form-input-file" accept="image/*">
            <small class="form-hint">Formats acceptés : JPG, PNG, GIF, WEBP (optionnel - logo par défaut si vide)</small>
            
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
=======
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
>>>>>>> 70a1b443d163ee0a60357ea7d5e6588e414d81f3
</html>
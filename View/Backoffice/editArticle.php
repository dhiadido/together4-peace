<<<<<<< HEAD
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
=======
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
>>>>>>> 70a1b443d163ee0a60357ea7d5e6588e414d81f3
</html>
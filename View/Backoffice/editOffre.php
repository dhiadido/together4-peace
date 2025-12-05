<?php
require_once(__DIR__ . '/../../Controller/OffreController.php');
require_once(__DIR__ . '/../../Model/Offres.php');
require_once(__DIR__ . '/../../Controller/ArticleController.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$controller = new OffreController();
$articleController = new ArticleController();
$articles = $articleController->listArticles();

// Récupération de l'offre existante
$r = $controller->getOffreById($id);
if (!$r) {
    header('Location: OffreList.php');
    exit;
}

$selectedArticleId = isset($r['article']) ? $r['article'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gestion image : garder l'ancienne si aucune nouvelle n'est uploadée
    $imagePath = $r['image'];
    
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
        $imagePath = $controller->handleImageUpload();
    }

    // Création de l'objet Offre
    $offre = new Offre(
        $_POST['nom'],
        $_POST['description'],
        floatval($_POST['prix']),
        $_POST['categorie'],
        $_POST['categorie_probleme'],
        $_POST['contact'],
        $imagePath,
        intval($_POST['article_id'])
    );

    // Mise à jour dans la base
    $controller->updateOffre($offre, $id);

    // Redirection
    header('Location: OffreList.php');
    exit;
}

include "template.php";
?>

<h1>Modifier une offre</h1>

<div class="form-container">
    <form method="post" enctype="multipart/form-data" class="admin-form">

        <div class="form-group">
            <label class="form-label">Nom du spécialiste</label>
            <input name="nom" type="text" class="form-input" value="<?= htmlspecialchars($r['nom_specialiste']) ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" rows="4" class="form-input"><?= htmlspecialchars($r['description']) ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Prix (DT)</label>
                <input name="prix" type="number" step="0.01" class="form-input" value="<?= $r['prix'] ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Catégorie</label>
                <input name="categorie" type="text" class="form-input" value="<?= htmlspecialchars($r['categorie']) ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Catégorie problème</label>
                <input name="categorie_probleme" type="text" class="form-input" value="<?= htmlspecialchars($r['categorie_probleme']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Contact</label>
                <input name="contact" type="text" class="form-input" value="<?= htmlspecialchars($r['contact']) ?>" required>
            </div>
        </div>

        <!-- Select Article -->
        <div class="form-group">
            <label class="form-label">Associer un article</label>
            <select name="article_id" class="form-input" required>
                <option value="">-- Choisir un article --</option>
                <?php foreach ($articles as $art): ?>
                    <option value="<?= $art['id_article'] ?>" <?= $selectedArticleId == $art['id_article'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($art['titre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Image actuelle / Upload -->
        <div class="form-group">
            <label class="form-label">Image actuelle</label>
            <?php if (!empty($r['image'])): ?>
                <div class="current-image" style="margin: 15px 0;">
                    <img src="<?= htmlspecialchars($r['image']) ?>" 
                         alt="Image actuelle"
                         style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                </div>
            <?php endif; ?>
            
            <label class="form-label" style="margin-top: 20px;">Changer l'image (optionnel)</label>
            <input name="image" type="file" id="imageInput" accept="image/*" class="form-input-file">
            <small class="form-hint">Formats acceptés : JPG, PNG, GIF, WEBP (laisser vide pour garder l'image actuelle)</small>
            
            <div class="image-preview" id="imagePreview" style="margin-top: 15px;"></div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Enregistrer les modifications
            </button>
            <a href="OffreList.php" class="btn btn-secondary">
                <i class="fa fa-times"></i> Annuler
            </a>
        </div>

    </form>
</div>

<script src="../../assets/js/validationOffre.js"></script>

    </main>
</div>

</body>
</html>
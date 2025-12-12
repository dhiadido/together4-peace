<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../../Controller/OffreController.php');
require_once(__DIR__ . '/../../Model/Offres.php');
require_once(__DIR__ . '/../../Controller/ArticleController.php');

$articleController = new ArticleController();
$articles = $articleController->listArticles();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $controller = new OffreController();
    
    $imagePath = $controller->handleImageUpload();

    $offre = new Offre(
        $_POST['nom'],
        $_POST['description'],
        floatval($_POST['prix']),
        $_POST['categorie'],
        $_POST['categorie_probleme'],
        $_POST['contact'],
        $imagePath,  // Peut être NULL maintenant
        intval($_POST['article_id'])
    );
    
    $controller->addOffre($offre);
    
    header('Location: OffreList.php');
    exit;
}

include "template.php"; 
?>

<h1>Ajouter une offre</h1>

<div class="form-container">
    <form method="post" enctype="multipart/form-data" class="admin-form">

        <div class="form-group">
            <label class="form-label">Nom du spécialiste *</label>
            <input name="nom" type="text" class="form-input" placeholder="Ex: Dr. Ahmed Ben Salah">
        </div>

        <div class="form-group">
            <label class="form-label">Description *</label>
            <textarea name="description" rows="4" class="form-input" placeholder="Décrivez les services offerts..."></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Prix (DT) *</label>
                <input name="prix" type="number" step="0.01" class="form-input" placeholder="Ex: 50.00">
            </div>

            <div class="form-group">
                <label class="form-label">Catégorie</label>
                <input name="categorie" type="text" class="form-input" placeholder="Ex: Psychologue, Coach...">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Catégorie problème</label>
                <input name="categorie_probleme" type="text" class="form-input" placeholder="Ex: stress, anxiété...">
            </div>

            <div class="form-group">
                <label class="form-label">Contact</label>
                <input name="contact" type="text" class="form-input" placeholder="Email ou numéro">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Associer un article *</label>
            <select name="article_id" class="form-input">
                <option value="">-- Choisir un article --</option>
                <?php foreach($articles as $art): ?>
                    <option value="<?= $art['id_article'] ?>">
                        <?= htmlspecialchars($art['titre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Photo du spécialiste (optionnel)</label>
            <input name="image" type="file" id="imageInput" accept="image/*" class="form-input-file">
            <small class="form-hint">Formats acceptés : JPG, PNG, GIF, WEBP. Aucune image par défaut si laissé vide.</small>

            <div class="image-preview" id="imagePreview" style="margin-top: 15px;"></div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-check"></i> Créer l'offre
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
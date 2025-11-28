<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Page loaded...<br>";

require_once(__DIR__ . '/../../Controller/OffreController.php');
require_once(__DIR__ . '/../../Model/Offres.php');

echo "Files included...<br>";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    echo "POST request detected...<br>";
    echo "<pre>POST data: ";
    print_r($_POST);
    echo "</pre>";
    
    $controller = new OffreController();
    echo "Controller created...<br>";
    
    // Handle image upload
    $imagePath = $controller->handleImageUpload();
    echo "Image path: " . $imagePath . "<br>";
    
    // Create Offre object
    $offre = new Offre(
        $_POST['nom'],
        $_POST['description'],
        floatval($_POST['prix']),
        $_POST['categorie'],
        $_POST['categorie_probleme'],
        $_POST['contact'],
        $imagePath
    );
    
    echo "Offre object created...<br>";
    
    // Add offre to database
    $controller->addOffre($offre);
    
    echo "Offre added! Redirecting...<br>";
    
    // Redirect
    header('Location: OffreList.php');
    exit;
}

include "template.php"; 
?>

<h2 class="mb-4">Ajouter une offre</h2>

<form method="post" enctype="multipart/form-data" class="card p-4 shadow">

    <div class="mb-3">
        <label class="form-label">Nom</label>
        <input name="nom" type="text" class="form-control" placeholder="Ex: Dr. Ahmed Ben Salah" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control" placeholder="Décrivez les services offerts..."></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Prix</label>
        <input name="prix" type="number" step="0.01" class="form-control" placeholder="Ex: 50.00">
    </div>

    <div class="mb-3">
        <label class="form-label">Catégorie</label>
        <input name="categorie" type="text" class="form-control" placeholder="Ex: Psychologue, Coach...">
    </div>

    <div class="mb-3">
        <label class="form-label">Catégorie problème</label>
        <input name="categorie_probleme" type="text" class="form-control" placeholder="Ex: stress, anxiété...">
    </div>

    <div class="mb-3">
        <label class="form-label">Contact</label>
        <input name="contact" type="text" class="form-control" placeholder="email ou numéro">
    </div>

    <div class="mb-3">
        <label class="form-label">Photo du spécialiste</label>
        <input name="image" type="file" id="imageInput" accept="image/*" class="form-control">
        <small class="text-muted">Formats acceptés : JPG, PNG, GIF, WEBP</small>

        <div class="mt-3" id="imagePreview"></div>
    </div>

    <button type="submit" class="btn btn-success">Créer</button><br>
    <a href="OffreList.php" class="btn btn-secondary">Annuler</a>

</form>

<script src="../../assets/js/validationOffre.js"></script>

</div>
</body>
</html>
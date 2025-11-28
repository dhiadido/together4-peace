<?php
require_once(__DIR__ . '/../../Controller/OffreController.php');
require_once(__DIR__ . '/../../Model/Offres.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$controller = new OffreController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create Offre object with updated data
    $offre = new Offre(
        $_POST['nom'],
        $_POST['description'],
        floatval($_POST['prix']),
        $_POST['categorie'],
        $_POST['categorie_probleme'],
        $_POST['contact'],
        $_POST['image'] ?: 'assets/placeholder.png'
    );
    
    // Update offre in database
    $controller->updateOffre($offre, $id);
    
    // Redirect
    header('Location: OffreList.php');
    exit;
}

// Get existing offre data
$r = $controller->getOffreById($id);

include "template.php";
?>

<h2 class="mb-4">Modifier une offre</h2>

<form method="post" class="card p-4 shadow">

    <div class="mb-3">
        <label class="form-label">Nom</label>
        <input name="nom" type="text" class="form-control"
               value="<?= htmlspecialchars($r['nom_specialiste']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control"><?= htmlspecialchars($r['description']) ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Prix</label>
        <input name="prix" type="number" step="0.01" class="form-control"
               value="<?= $r['prix'] ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Catégorie</label>
        <input name="categorie" type="text" class="form-control"
               value="<?= htmlspecialchars($r['categorie']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Catégorie problème</label>
        <input name="categorie_probleme" type="text" class="form-control"
               value="<?= htmlspecialchars($r['categorie_probleme']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Contact</label>
        <input name="contact" type="text" class="form-control"
               value="<?= htmlspecialchars($r['contact']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Image (URL ou chemin)</label>

        <div class="mb-2">
            <img src="/Projet%20-%20Copie/<?= htmlspecialchars($r['image']) ?>" 
                 style="width:150px;height:100px;object-fit:cover;border-radius:8px">
        </div>

        <input name="image" type="text" class="form-control"
               value="<?= htmlspecialchars($r['image']) ?>">
        <small class="text-muted">Laissez ce champ tel quel si vous ne changez pas l'image.</small>
    </div>

    <button type="submit" class="btn btn-warning">Enregistrer</button>
</form>

    <script src="../../assets/js/validationOffre.js"></script>

</div>
</body>
</html>
<?php
require_once(__DIR__ . '/../../config.php');
$pdo = config::getConnexion();

// ID à modifier
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Traitement formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sql = 'UPDATE offres_specialistes 
            SET nom_specialiste=:nom, description=:desc, prix=:prix, categorie=:cat, 
                categorie_probleme=:cprob, contact=:contact, image=:img 
            WHERE id_offre=:id';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nom'     => $_POST['nom'],
        ':desc'    => $_POST['description'],
        ':prix'    => floatval($_POST['prix']),
        ':cat'     => $_POST['categorie'],
        ':cprob'   => $_POST['categorie_probleme'],
        ':contact' => $_POST['contact'],
        ':img'     => $_POST['image'] ?: 'assets/placeholder.png',
        ':id'      => $id
    ]);

    header('Location: back_offre_list.php');
    exit;
}

// Charger l'offre actuelle
$stmt = $pdo->prepare('SELECT * FROM offres_specialistes WHERE id_offre = :id');
$stmt->execute([':id' => $id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);

// Charger le template (sidebar + topbar + content)
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
        <small class="text-muted">Laissez ce champ tel quel si vous ne changez pas l’image.</small>
    </div>

    <button type="submit" class="btn btn-warning">Enregistrer</button>
</form>

</div>
</body>
</html>

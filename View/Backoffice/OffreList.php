<?php 
require_once(__DIR__ . '/../../Controller/OffreController.php');

$controller = new OffreController();
$rows = $controller->listOffres();

include "template.php";
?>

<h1 class="mb-4">Gestion des Offres</h1>

<a href="addOffre.php" class="btn btn-primary mb-3">Ajouter une offre</a>

<div class="card shadow">
    <div class="card-body p-0">
        <table class="table table-bordered table-hover m-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Date</th>
                    <th style="width: 160px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $r): ?>
                <tr>
                    <td><?= $r['id_offre'] ?></td>

                    <td>
                        <img src="../../assets/images/logo.png"
                             alt="Photo de <?= htmlspecialchars($r['nom_specialiste']) ?>"
                             style="width: 110px; height: 80px; object-fit: cover; border-radius: 8px;">
                    </td>

                    <td><?= htmlspecialchars($r['nom_specialiste']) ?></td>
                    <td><?= htmlspecialchars($r['categorie_probleme']) ?></td>
                    <td><?= number_format($r['prix'], 2) ?> DT</td>
                    <td><?= $r['date_ajout'] ?></td>

                    <td>
                        <a href="editOffre.php?id=<?= $r['id_offre'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="deleteOffre.php?id=<?= $r['id_offre'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</body>
</html>
<?php 
require_once(__DIR__ . '/../../Controller/ArticleController.php');

$controller = new ArticleController();
$rows = $controller->listArticles();

include "template.php";
?>

<h1 class="mb-4">Gestion des Articles</h1>

<a href="addArticle.php" class="btn btn-primary mb-3">Ajouter un article</a>

<div class="card shadow">
    <div class="card-body p-0">
        <table class="table table-bordered table-hover m-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Thème</th>
                    <th style="width: 160px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $a): ?>
                <tr>
                    <td><?= $a['id_article'] ?></td>
                    <td><?= htmlspecialchars($a['titre']) ?></td>
                    <td><?= htmlspecialchars($a['theme']) ?></td>
                    <td>
                        <a href="editArticle.php?id=<?= $a['id_article'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="deleteArticle.php?id=<?= $a['id_article'] ?>" class="btn btn-danger btn-sm" 
                           onclick="return confirm('Supprimer cet article ?')">Supprimer</a>
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

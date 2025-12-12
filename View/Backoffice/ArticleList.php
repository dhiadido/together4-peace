<?php 
require_once(__DIR__ . '/../../Controller/ArticleController.php');

$controller = new ArticleController();
$rows = $controller->listArticles();

include "template.php";
?>

<h1>Gestion des Articles</h1>

<a href="addArticle.php" class="btn btn-primary mb-3">
    <i class="fa fa-plus"></i> Ajouter un article
</a>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Titre</th>
                <th>Thème</th>
                <th>Date</th>
                <th>Offres liées</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if(empty($rows)): ?>
            <tr class="empty-state">
                <td colspan="7">Aucun article disponible</td>
            </tr>
        <?php else: ?>
            <?php foreach($rows as $a): ?>
            <tr>
                <td><?= $a['id_article'] ?></td>
                
                <td>
                    <?php if (!empty($a['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/projet2/' . str_replace('../../', '', $a['image']))): ?>
                        <img src="<?= htmlspecialchars($a['image']) ?>"
                             alt="<?= htmlspecialchars($a['titre']) ?>"
                             class="table-img"
                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                    <?php else: ?>
                        <div class="no-image-placeholder" style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; border-radius: 8px; color: white; font-size: 24px;">
                            <i class="fa fa-newspaper"></i>
                        </div>
                    <?php endif; ?>
                </td>
                
                <td style="max-width: 250px;">
                    <strong><?= htmlspecialchars($a['titre']) ?></strong>
                    <?php if (!empty($a['resume'])): ?>
                        <br><small style="color: #666;"><?= htmlspecialchars(substr($a['resume'], 0, 80)) ?>...</small>
                    <?php endif; ?>
                </td>
                
                <td><?= htmlspecialchars($a['theme']) ?></td>
                
                <td><?= date('d/m/Y', strtotime($a['date_publication'])) ?></td>

                <td>
                    <?php 
                        $offres = $controller->getOffresByArticle($a['id_article']);
                        $count = count($offres);
                        
                        if ($count == 0) {
                            echo "<span class='badge badge-empty'>0 offre</span>";
                        } else {
                            echo "<span class='badge badge-success' title='Cliquez pour voir les détails'>"
                               . $count . " offre" . ($count > 1 ? "s" : "")
                               . "</span>";
                            
                            echo "<div class='offres-details' style='font-size:0.85em; margin-top:5px;'>";
                            foreach ($offres as $offre) {
                                echo "• " . htmlspecialchars($offre['nom_specialiste']) . "<br>";
                            }
                            echo "</div>";
                        }
                    ?>
                </td>

                <td>
                    <div class="action-buttons">
                        <a href="editArticle.php?id=<?= $a['id_article'] ?>" class="btn-action btn-edit">
                            <i class="fa fa-edit"></i> Modifier
                        </a>
                        <a href="deleteArticle.php?id=<?= $a['id_article'] ?>" 
                           class="btn-action btn-delete"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                            <i class="fa fa-trash"></i> Supprimer
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: bold;
}
.badge-empty {
    background-color: #e0e0e0;
    color: #666;
}
.badge-success {
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
}
.offres-details {
    color: #555;
    margin-top: 5px;
    font-size: 0.85em;
}
.table-img {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
</style>

</main>
</div>

</body>
</html>
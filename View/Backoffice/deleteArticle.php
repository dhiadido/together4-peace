<?php
require_once(__DIR__ . '/../../Controller/ArticleController.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id) {
    $controller = new ArticleController();
    $controller->deleteArticle($id);
}

header('Location: ArticleList.php');
exit;
?>
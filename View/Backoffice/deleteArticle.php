<<<<<<< HEAD
<?php
require_once(__DIR__ . '/../../Controller/ArticleController.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id) {
    $controller = new ArticleController();
    $controller->deleteArticle($id);
}

header('Location: ArticleList.php');
exit;
=======
<?php
require_once(__DIR__ . '/../../Controller/ArticleController.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id) {
    $controller = new ArticleController();
    $controller->deleteArticle($id);
}

header('Location: ArticleList.php');
exit;
>>>>>>> 70a1b443d163ee0a60357ea7d5e6588e414d81f3
?>
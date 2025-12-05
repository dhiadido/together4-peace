<?php
require_once(__DIR__ . '/ArticleController.php');

if (isset($_GET['offre_id'])) {
    $offreId = (int)$_GET['offre_id'];
    $controller = new ArticleController();
    $controller->trackOffreClick($offreId);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
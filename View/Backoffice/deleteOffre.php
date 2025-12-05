<?php
require_once(__DIR__ . '/../../Controller/OffreController.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id){
    $controller = new OffreController();
    $controller->deleteOffre($id);
}

header('Location: OffreList.php');
exit;
?>
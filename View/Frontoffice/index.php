<?php
require_once '../../config.php';
require_once '../../Controller.php';
require_once '../../Controller/HomeController.php';

$controller = new HomeController();
$controller->index();
?>
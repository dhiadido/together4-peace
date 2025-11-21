<?php
require_once __DIR__ . '/../../controller/UserController.php';

if (isset($_GET['id'])) {
    $userController = new UserController();
    
    try {
        $userController->deleteUser($_GET['id']);
        header('Location: listUsers.php?deleted=success');
    } catch (Exception $e) {
        header('Location: listUsers.php?deleted=error');
    }
} else {
    header('Location: listUsers.php');
}
exit;
?>
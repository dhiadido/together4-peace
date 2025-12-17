<?php
session_start();
unset($_SESSION['admin_id'], $_SESSION['admin_nom'], $_SESSION['admin_prenom'], $_SESSION['admin_email'], $_SESSION['admin_role']);
header('Location: ../views/admin-login.php');
exit;
?>


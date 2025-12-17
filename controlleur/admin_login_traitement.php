<?php
session_start();

require_once '../config/Database.php';
require_once '../models/user.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/admin-login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['error'] = 'Veuillez saisir votre email et votre mot de passe.';
    header('Location: ../views/admin-login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

$result = $userModel->login($email, $password);

if (is_array($result) && strtolower($result['role'] ?? '') === 'admin') {
    $_SESSION['admin_id'] = $result['id_utilisateur'];
    $_SESSION['admin_nom'] = $result['nom'];
    $_SESSION['admin_prenom'] = $result['prenom'] ?? '';
    $_SESSION['admin_email'] = $result['email'];
    $_SESSION['admin_role'] = $result['role'];

    header('Location: ../views/admin-dashboard.php');
    exit;
}

$_SESSION['error'] = is_string($result) ? $result : 'Accès refusé (droits insuffisants).';
header('Location: ../views/admin-login.php');
exit;
?>


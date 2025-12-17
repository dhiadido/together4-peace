<?php
session_start();

if (!isset($_SESSION['reset_email'])) {
    header("Location: ../views/forgot-password.php");
    exit;
}

require_once '../config/Database.php';
require_once '../models/PasswordReset.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $code = $_POST['code'] ?? '';
    $email = $_SESSION['reset_email'];
    
    if (empty($code)) {
        $_SESSION['error'] = "Veuillez entrer le code de vérification.";
        header("Location: ../views/verify-code.php");
        exit;
    }
    
    $database = new Database();
    $db = $database->getConnection();
    $passwordReset = new PasswordReset($db);
    
    if ($passwordReset->verifyCode($email, $code)) {
        $_SESSION['reset_verified'] = true;
        header("Location: ../views/reset-password.php");
        exit;
    } else {
        $_SESSION['error'] = "Code incorrect ou expiré. Veuillez réessayer.";
        header("Location: ../views/verify-code.php");
        exit;
    }
    
} else {
    header("Location: ../views/verify-code.php");
    exit;
}
?>
<?php
session_start();

if (!isset($_SESSION['reset_verified'])) {
    header("Location: ../views/forgot-password.php");
    exit;
}

require_once '../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $email = $_SESSION['reset_email'];
    
    if (empty($password) || empty($confirm)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
        header("Location: ../views/reset-password.php");
        exit;
    }
    
    if ($password !== $confirm) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header("Location: ../views/reset-password.php");
        exit;
    }
    
    if (strlen($password) < 6) {
        $_SESSION['error'] = "Le mot de passe doit contenir au moins 6 caractères.";
        header("Location: ../views/reset-password.php");
        exit;
    }
    
    // Mettre à jour le mot de passe
    $database = new Database();
    $db = $database->getConnection();
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "UPDATE user2 SET mot_de_passe = :password WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':email', $email);
    
    if ($stmt->execute()) {
        // Nettoyer les sessions
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_verified']);
        
        $_SESSION['success'] = "Votre mot de passe a été réinitialisé avec succès !";
        header("Location: ../views/login.php");
        exit;
    } else {
        $_SESSION['error'] = "Erreur lors de la réinitialisation. Veuillez réessayer.";
        header("Location: ../views/reset-password.php");
        exit;
    }
    
} else {
    header("Location: ../views/reset-password.php");
    exit;
}
?>

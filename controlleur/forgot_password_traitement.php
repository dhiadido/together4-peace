<?php
session_start();

require_once '../config/Database.php';
require_once '../models/user.php';
require_once '../models/PasswordReset.php';
require_once '../lib/EmailSender.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['resend'])) {
    
    $email = isset($_GET['resend']) ? ($_SESSION['reset_email'] ?? '') : trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $_SESSION['error'] = "Veuillez entrer votre adresse email.";
        header("Location: ../views/forgot-password.php");
        exit;
    }
    
    // Vérifier si l'email existe
    $database = new Database();
    $db = $database->getConnection();
    
    $stmt = $db->prepare("SELECT nom FROM user2 WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        $_SESSION['error'] = "Aucun compte trouvé avec cet email.";
        header("Location: ../views/forgot-password.php");
        exit;
    }
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Générer le code
    $passwordReset = new PasswordReset($db);
    
    $code = $passwordReset->generateCode($email);
    
    if (!$code) {
        $_SESSION['error'] = "Erreur lors de la génération du code.";
        header("Location: ../views/forgot-password.php");
        exit;
    }
    
    // Envoyer l'email
    if (EmailSender::sendVerificationCode($email, $code, $user['nom'])) {
        $_SESSION['reset_email'] = $email;
        $_SESSION['success'] = "Un code de vérification a été envoyé à votre email.";
        header("Location: ../views/verify-code.php");
        exit;
    } else {
        $_SESSION['error'] = "Erreur lors de l'envoi de l'email. Veuillez réessayer.";
        header("Location: ../views/forgot-password.php");
        exit;
    }
    
} else {
    header("Location: ../views/forgot-password.php");
    exit;
}
?>
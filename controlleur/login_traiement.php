<?php
// controlleur/login_traitement.php
session_start();

require_once '../config/Database.php';
require_once '../config/RecaptchaConfig.php';
require_once '../models/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérification reCAPTCHA
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    $remoteIp = $_SERVER['REMOTE_ADDR'] ?? null;
    $recaptchaResult = RecaptchaConfig::verify($recaptchaResponse, $remoteIp);
    
    if (!$recaptchaResult['success']) {
        $_SESSION['error'] = 'Vérification reCAPTCHA échouée. Veuillez réessayer.';
        header("Location: ../views/login.php");
        exit;
    }

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $database = new Database();
    $db = $database->getConnection();
    $userModel = new User($db);

    $result = $userModel->login($email, $password);

    if (is_array($result)) {
        // Connexion OK - Stocker toutes les infos nécessaires
        $_SESSION['user_id']           = $result['id_utilisateur'];
        $_SESSION['user_nom']          = $result['nom'];
        $_SESSION['user_prenom']       = $result['prenom'] ?? '';
        $_SESSION['user_email']        = $result['email'];
        $_SESSION['user_role']         = $result['role'] ?? 'Membre';
        $_SESSION['user_photo']        = $result['photo'] ?? 'default-avatar.jpg';
        $_SESSION['date_inscription']  = $result['date_inscription'] ?? date('Y-m-d');

        // Redirection vers le dashboard
        header("Location: ../views/dashboard.php");
        exit;
    } else {
        // Erreur de connexion
        $_SESSION['error'] = $result;
        header("Location: ../views/login.php");
        exit;
    }

} else {
    header("Location: ../views/login.php");
    exit;
}
?>

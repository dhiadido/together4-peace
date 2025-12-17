<?php
// controlleur/traitement.php

session_start();

require_once dirname(__DIR__) . '/config/Database.php';
require_once dirname(__DIR__) . '/config/RecaptchaConfig.php';
require_once dirname(__DIR__) . '/models/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérification reCAPTCHA
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    $remoteIp = $_SERVER['REMOTE_ADDR'] ?? null;
    $recaptchaResult = RecaptchaConfig::verify($recaptchaResponse, $remoteIp);
    
    if (!$recaptchaResult['success']) {
        $_SESSION['error'] = 'Vérification reCAPTCHA échouée. Veuillez réessayer.';
        header("Location: ../views/register.html");
        exit;
    }

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    // Vérif côté serveur
    if ($password !== $confirm) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header("Location: ../views/register.html");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Adresse e-mail invalide.";
        header("Location: ../views/register.html");
        exit;
    }

    // Connexion BDD
    $database = new Database();
    $db = $database->getConnection();
    $userModel = new User($db);

    // On met tout le username dans 'nom', prénom vide
    $nom = $username;
    $prenom = "";

    $result = $userModel->register($nom, $prenom, $email, $password, 'user');

    if ($result === true) {
        // Stocker l'email dans la session pour l'enregistrement Face ID
        $_SESSION['registering_email'] = $email;
        $_SESSION['success'] = "Compte créé avec succès !";
        header("Location: ../views/face_register.php");
        exit;
    } else {
        $_SESSION['error'] = $result;
        header("Location: ../views/register.html");
        exit;
    }

} else {
    header("Location: ../views/register.html");
    exit;
}
?>

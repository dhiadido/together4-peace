<?php
// controlleur/traitement.php

session_start();

require_once dirname(__DIR__) . '/config/Database.php';
require_once dirname(__DIR__) . '/models/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    // Vérif côté serveur
    if ($password !== $confirm) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header("Location: ../views/index.html");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Adresse e-mail invalide.";
        header("Location: ../views/index.html");
        exit;
    }

    // Connexion BDD
    $database = new Database();
    $db = $database->getConnection();
    $userModel = new User($db);

    // On met tout le username dans 'nom', prénom vide (tu peux l'étendre plus tard)
    $nom = $username;
    $prenom = "";

    $result = $userModel->register($nom, $prenom, $email, $password, 'user');

    if ($result === true) {
        $_SESSION['success'] = "Compte créé avec succès ! Vous pouvez vous connecter.";
        header("Location: ../views/login.html");
        exit;
    } else {
        $_SESSION['error'] = $result;
        header("Location: ../views/index.html");
        exit;
    }

} else {
    header("Location: ../views/index.html");
    exit;
}

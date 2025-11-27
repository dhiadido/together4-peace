<?php
// controlleur/login_traitement.php
session_start();

require_once '../config/Database.php';
require_once '../models/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $database = new Database();
    $db = $database->getConnection();
    $userModel = new User($db);

    $result = $userModel->login($email, $password);

    if (is_array($result)) {
        // Connexion OK
        $_SESSION['user_id']   = $result['id_utilisateur'];
        $_SESSION['user_nom']  = $result['nom'];
        $_SESSION['user_role'] = $result['role'];

        header("Location: ../views/dashboard.php");
        exit;
    } else {
        // Erreur message (email ou mdp)
        $_SESSION['error'] = $result;
        header("Location: ../views/login.html");
        exit;
    }

} else {
    header("Location: ../views/login.html");
    exit;
}
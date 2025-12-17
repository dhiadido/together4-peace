<?php
session_start();

// Vérifier l'authentification admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../views/admin-login.php');
    exit;
}

require_once '../config/Database.php';
require_once '../models/user.php';

$action = $_POST['action'] ?? '';

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

function redirectWithMessage($type, $message) {
    $_SESSION[$type] = $message;
    header('Location: ../views/admin-dashboard.php');
    exit;
}

switch ($action) {
    case 'create':
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        if ($nom === '' || $email === '' || $password === '') {
            redirectWithMessage('error', 'Nom, email et mot de passe sont requis pour créer un compte.');
        }

        $ok = $userModel->createUser($nom, $prenom, $email, $password, $role);
        if ($ok) {
            redirectWithMessage('success', 'Utilisateur créé avec succès.');
        }
        redirectWithMessage('error', 'Erreur lors de la création du compte.');

    case 'update':
        $id = (int)($_POST['id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'user';
        $password = $_POST['password'] ?? '';

        if ($id <= 0 || $nom === '' || $email === '') {
            redirectWithMessage('error', 'ID, nom et email sont requis pour la mise à jour.');
        }

        $ok = $userModel->updateUser($id, $nom, $prenom, $email, $role, $password ?: null);
        if ($ok) {
            redirectWithMessage('success', 'Utilisateur mis à jour avec succès.');
        }
        redirectWithMessage('error', 'Erreur lors de la mise à jour du compte.');

    case 'delete':
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            redirectWithMessage('error', 'ID utilisateur invalide.');
        }
        // Empêcher la suppression de soi-même
        if ($id === (int)$_SESSION['admin_id']) {
            redirectWithMessage('error', 'Vous ne pouvez pas supprimer votre propre compte administrateur.');
        }
        $ok = $userModel->deleteUser($id);
        if ($ok) {
            redirectWithMessage('success', 'Utilisateur supprimé avec succès.');
        }
        redirectWithMessage('error', 'Erreur lors de la suppression du compte.');

    default:
        redirectWithMessage('error', 'Action non reconnue.');
}
?>


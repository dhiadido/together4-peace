<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit;
}

require_once '../config/Database.php';
require_once '../models/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($nom)) {
        $_SESSION['error'] = 'Le nom est requis.';
        header("Location: ../views/edit-profile.php");
        exit;
    }

    if (empty($email)) {
        $_SESSION['error'] = 'L\'adresse email est requise.';
        header("Location: ../views/edit-profile.php");
        exit;
    }

    // Vérifier qu'il n'y a pas d'espaces dans l'email
    if (strpos($email, ' ') !== false) {
        $_SESSION['error'] = 'L\'adresse email ne doit pas contenir d\'espaces.';
        header("Location: ../views/edit-profile.php");
        exit;
    }

    // Validation du mot de passe si fourni
    if (!empty($password)) {
        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Les mots de passe ne correspondent pas.';
            header("Location: ../views/edit-profile.php");
            exit;
        }
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Le mot de passe doit contenir au moins 6 caractères.';
            header("Location: ../views/edit-profile.php");
            exit;
        }
    }

    // Gestion de la photo
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['photo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error'] = 'Type de fichier non autorisé. Utilisez JPEG, PNG, GIF ou WebP.';
            header("Location: ../views/edit-profile.php");
            exit;
        }

        if ($file['size'] > $maxSize) {
            $_SESSION['error'] = 'Le fichier est trop volumineux. Taille maximale : 5MB.';
            header("Location: ../views/edit-profile.php");
            exit;
        }

        // Créer le dossier uploads s'il n'existe pas
        $uploadDir = '../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Générer un nom de fichier unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = 'profile_' . $userId . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $photo = $newFileName;

            // Supprimer l'ancienne photo si elle existe et n'est pas la photo par défaut
            $database = new Database();
            $db = $database->getConnection();
            $userModel = new User($db);
            $oldUser = $userModel->getUserById($userId);
            if ($oldUser && !empty($oldUser['photo']) && $oldUser['photo'] !== 'default-avatar.jpg') {
                $oldPhotoPath = $uploadDir . $oldUser['photo'];
                if (file_exists($oldPhotoPath)) {
                    @unlink($oldPhotoPath);
                }
            }
        } else {
            $_SESSION['error'] = 'Erreur lors de l\'upload de la photo.';
            header("Location: ../views/edit-profile.php");
            exit;
        }
    }

    // Mettre à jour le profil
    $database = new Database();
    $db = $database->getConnection();
    $userModel = new User($db);

    $result = $userModel->updateProfile(
        $userId,
        $nom,
        $prenom,
        $email,
        $photo,
        !empty($password) ? $password : null
    );

    if (is_array($result)) {
        // Mise à jour réussie - mettre à jour la session
        $_SESSION['user_nom'] = $result['nom'];
        $_SESSION['user_prenom'] = $result['prenom'] ?? '';
        $_SESSION['user_email'] = $result['email'];
        $_SESSION['user_photo'] = $result['photo'] ?? 'default-avatar.jpg';

        $_SESSION['success'] = 'Profil mis à jour avec succès !';
        header("Location: ../views/dashboard.php");
        exit;
    } else {
        // Erreur
        $_SESSION['error'] = $result;
        header("Location: ../views/edit-profile.php");
        exit;
    }
} else {
    header("Location: ../views/edit-profile.php");
    exit;
}
?>


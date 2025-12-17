<?php
session_start();

require_once '../config/Database.php';
require_once '../models/user.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Récupérer les données JSON
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['face_embedding']) || empty($data['face_embedding'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Embedding facial manquant.'
        ]);
        exit;
    }

    $faceEmbedding = $data['face_embedding']; // Array

    // Connexion BDD
    $database = new Database();
    $db = $database->getConnection();
    $userModel = new User($db);

    // Vérifier le visage
    $result = $userModel->loginByFace($faceEmbedding);

    if (is_array($result)) {
        // Connexion OK - Stocker toutes les infos nécessaires
        $_SESSION['user_id']           = $result['id_utilisateur'];
        $_SESSION['user_nom']          = $result['nom'];
        $_SESSION['user_prenom']       = $result['prenom'] ?? '';
        $_SESSION['user_email']        = $result['email'];
        $_SESSION['user_role']         = $result['role'] ?? 'Membre';
        $_SESSION['user_photo']        = $result['photo'] ?? 'default-avatar.jpg';
        $_SESSION['date_inscription']  = $result['date_inscription'] ?? date('Y-m-d');

        echo json_encode([
            'success' => true,
            'message' => 'Connexion réussie !'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => $result // Message d'erreur
        ]);
    }

} else {
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ]);
}
?>



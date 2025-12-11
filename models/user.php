<?php
// models/user.php

class User {
    private $conn;
    private $table = "user2";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($nom, $prenom, $email, $password, $role = 'user') {
        $checkSql = "SELECT id_utilisateur FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "Cet email est déjà utilisé.";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO " . $this->table . " (nom, prenom, email, mot_de_passe, role)
                VALUES (:nom, :prenom, :email, :mot_de_passe, :role)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $hashedPassword);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            return true;
        } else {
            return "Erreur lors de l'inscription.";
        }
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['mot_de_passe'])) {
                return $user;
            } else {
                return "Mot de passe incorrect.";
            }
        } else {
            return "Aucun compte trouvé avec cet email.";
        }
    }

    // Enregistrer l'embedding facial pour un utilisateur
    public function saveFaceEmbedding($email, $faceEmbedding) {
        $sql = "UPDATE " . $this->table . " SET face_embedding = :face_embedding WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':face_embedding', $faceEmbedding);
        $stmt->bindParam(':email', $email);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Récupérer tous les utilisateurs avec leurs embeddings faciaux
    public function getAllUsersWithFaceEmbeddings() {
        $sql = "SELECT id_utilisateur, email, nom, prenom, face_embedding FROM " . $this->table . " WHERE face_embedding IS NOT NULL AND face_embedding != ''";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Connexion par Face ID
    public function loginByFace($faceEmbedding) {
        $users = $this->getAllUsersWithFaceEmbeddings();
        
        foreach ($users as $user) {
            if (!empty($user['face_embedding'])) {
                $storedEmbedding = json_decode($user['face_embedding'], true);
                if ($storedEmbedding && $this->compareFaceEmbeddings($faceEmbedding, $storedEmbedding)) {
                    // Récupérer toutes les infos de l'utilisateur
                    $sql = "SELECT * FROM " . $this->table . " WHERE id_utilisateur = :id LIMIT 1";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(':id', $user['id_utilisateur']);
                    $stmt->execute();
                    return $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        
        return "Aucun visage correspondant trouvé.";
    }

    // Comparer deux embeddings faciaux (distance euclidienne)
    private function compareFaceEmbeddings($embedding1, $embedding2, $threshold = 0.6) {
        if (count($embedding1) !== count($embedding2)) {
            return false;
        }
        
        $distance = 0;
        for ($i = 0; $i < count($embedding1); $i++) {
            $distance += pow($embedding1[$i] - $embedding2[$i], 2);
        }
        $distance = sqrt($distance);
        
        return $distance <= $threshold;
    }

    // Trouver un utilisateur par email ou google_id
    public function findByEmailOrGoogleId($email, $googleId) {
        $sql = "SELECT * FROM " . $this->table . " WHERE email = :email OR google_id = :google_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':google_id', $googleId);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // Inscription avec Google
    public function registerWithGoogle($nom, $prenom, $email, $googleId, $photo = null, $role = 'user') {
        // Vérifier si l'email ou le google_id existe déjà
        $checkSql = "SELECT id_utilisateur FROM " . $this->table . " WHERE email = :email OR google_id = :google_id";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':google_id', $googleId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "Cet email ou compte Google est déjà utilisé.";
        }

        // Vérifier si la colonne date_inscription existe
        $hasDateColumn = $this->columnExists('date_inscription');
        
        // Insérer le nouvel utilisateur
        if ($hasDateColumn) {
            $sql = "INSERT INTO " . $this->table . " (nom, prenom, email, google_id, photo, role, date_inscription)
                    VALUES (:nom, :prenom, :email, :google_id, :photo, :role, NOW())";
        } else {
            $sql = "INSERT INTO " . $this->table . " (nom, prenom, email, google_id, photo, role)
                    VALUES (:nom, :prenom, :email, :google_id, :photo, :role)";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':google_id', $googleId);
        $stmt->bindParam(':photo', $photo);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            // Récupérer l'utilisateur créé
            $sql = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return "Erreur lors de l'inscription avec Google.";
        }
    }

    // Mettre à jour le google_id d'un utilisateur existant
    public function updateGoogleId($userId, $googleId) {
        $sql = "UPDATE " . $this->table . " SET google_id = :google_id WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':google_id', $googleId);
        $stmt->bindParam(':id', $userId);
        
        return $stmt->execute();
    }

    // Mettre à jour le profil utilisateur (nom, prénom, email, photo, mot de passe optionnel)
    public function updateProfile($userId, $nom, $prenom, $email, $photo = null, $password = null) {
        // Vérifier si l'email existe déjà pour un autre utilisateur
        $checkSql = "SELECT id_utilisateur FROM " . $this->table . " WHERE email = :email AND id_utilisateur != :id";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "Cet email est déjà utilisé par un autre compte.";
        }

        // Construire la requête SQL dynamiquement selon les champs fournis
        $updates = [];
        $params = [];

        $updates[] = "nom = :nom";
        $params[':nom'] = $nom;

        $updates[] = "prenom = :prenom";
        $params[':prenom'] = $prenom;

        $updates[] = "email = :email";
        $params[':email'] = $email;

        if ($photo !== null) {
            $updates[] = "photo = :photo";
            $params[':photo'] = $photo;
        }

        if ($password !== null && $password !== '') {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updates[] = "mot_de_passe = :mot_de_passe";
            $params[':mot_de_passe'] = $hashedPassword;
        }

        $sql = "UPDATE " . $this->table . " SET " . implode(", ", $updates) . " WHERE id_utilisateur = :id";
        $params[':id'] = $userId;

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        if ($stmt->execute()) {
            // Récupérer l'utilisateur mis à jour
            $sql = "SELECT * FROM " . $this->table . " WHERE id_utilisateur = :id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return "Erreur lors de la mise à jour du profil.";
        }
    }

    // Récupérer un utilisateur par son ID
    public function getUserById($userId) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id_utilisateur = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /* ---------- Admin helpers ---------- */
    public function getAllUsers() {
        // Vérifier si la colonne date_inscription existe
        $hasDateColumn = $this->columnExists('date_inscription');
        
        if ($hasDateColumn) {
            $sql = "SELECT id_utilisateur, nom, prenom, email, role, date_inscription FROM " . $this->table . " ORDER BY date_inscription DESC";
        } else {
            $sql = "SELECT id_utilisateur, nom, prenom, email, role FROM " . $this->table . " ORDER BY id_utilisateur DESC";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ajouter une date par défaut si la colonne n'existe pas
        if (!$hasDateColumn) {
            foreach ($users as &$user) {
                $user['date_inscription'] = 'N/A';
            }
        }
        
        return $users;
    }
    
    // Vérifier si une colonne existe dans la table
    private function columnExists($columnName) {
        try {
            $sql = "SHOW COLUMNS FROM " . $this->table . " LIKE :column";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':column', $columnName);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function createUser($nom, $prenom, $email, $password, $role = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Vérifier si la colonne date_inscription existe
        $hasDateColumn = $this->columnExists('date_inscription');
        
        if ($hasDateColumn) {
            $sql = "INSERT INTO " . $this->table . " (nom, prenom, email, mot_de_passe, role, date_inscription)
                    VALUES (:nom, :prenom, :email, :mot_de_passe, :role, NOW())";
        } else {
            $sql = "INSERT INTO " . $this->table . " (nom, prenom, email, mot_de_passe, role)
                    VALUES (:nom, :prenom, :email, :mot_de_passe, :role)";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $hashedPassword);
        $stmt->bindParam(':role', $role);
        return $stmt->execute();
    }

    public function updateUser($id, $nom, $prenom, $email, $role, $password = null) {
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE " . $this->table . " SET nom = :nom, prenom = :prenom, email = :email, role = :role, mot_de_passe = :mot_de_passe WHERE id_utilisateur = :id";
        } else {
            $sql = "UPDATE " . $this->table . " SET nom = :nom, prenom = :prenom, email = :email, role = :role WHERE id_utilisateur = :id";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id);
        if ($password) {
            $stmt->bindParam(':mot_de_passe', $hashedPassword);
        }
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM " . $this->table . " WHERE id_utilisateur = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function countUsers() {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }

    public function countByRole($role) {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE role = :role";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }

    public function countRecent($days = 7) {
        // Vérifier si la colonne date_inscription existe
        $hasDateColumn = $this->columnExists('date_inscription');
        
        if ($hasDateColumn) {
            $sql = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE date_inscription >= DATE_SUB(NOW(), INTERVAL :days DAY)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':days', $days, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$row['total'];
        } else {
            // Si la colonne n'existe pas, retourner 0 ou le total des utilisateurs
            return 0;
        }
    }
}
?>
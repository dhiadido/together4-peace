<?php
// models/user.php

class User {
    private $conn;
    private $table = "user2"; // table user

    public function __construct($db) {
        $this->conn = $db;
    }

    // Inscription
    public function register($nom, $prenom, $email, $password, $role = 'user') {
        // Vérifier si email existe déjà
        $checkSql = "SELECT id_utilisateur FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "Cet email est déjà utilisé.";
        }

        // Hasher le mot de passe
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

    // Connexion
    public function login($email, $password) {
        $sql = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['mot_de_passe'])) {
                return $user; // OK
            } else {
                return "Mot de passe incorrect.";
            }
        } else {
            return "Aucun compte trouvé avec cet email.";
        }
    }
}

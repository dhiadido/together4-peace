<?php
// config/Database.php

class Database {
    private $host = "localhost";
    private $db_name = "baseuser";   // nom de ta base (vue sur le screen)
    private $username = "root";  // XAMPP par défaut
    private $password = "";      // XAMPP : mot de passe vide
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }

        return $this->conn;
    }
}
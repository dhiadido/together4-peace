<?php

class config
{
    private static $pdo = null;
    private static $config = [
        'google_translate_api_key' => 'YOUR_API_KEY_HERE' // Remplacez par votre clé API Google Translate
    ];

    public static function getConnexion() {
        // Database credentials
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'diakité';

        // Use a singleton pattern to ensure only one database connection is used
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
                // Uncomment below for debugging purposes
                // echo "Connected successfully";
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }

    public static function get($key) {
        return self::$config[$key] ?? null;
    }
}

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', '0stage');
define('DB_USER', 'root');
define('DB_PASS', '');

// Fonction de connexion à la base de données
function getConnection() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }
}

// Fonction pour créer la base de données si elle n'existe pas
function createDatabase() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Créer la base de données si elle n'existe pas
        $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // Sélectionner la base de données
        $pdo->exec("USE " . DB_NAME);
        
        // Créer la table utilisateur si elle n'existe pas
        $sql = "CREATE TABLE IF NOT EXISTS utilisateur (
            id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(50) NOT NULL,
            prenom VARCHAR(50) NOT NULL,
            date_naissance DATE NOT NULL,
            num_cin VARCHAR(10) NOT NULL UNIQUE,
            adresse VARCHAR(255),
            num_telephone VARCHAR(15),
            email VARCHAR(100) NOT NULL UNIQUE,
            mot_de_passe VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        
        return true;
    } catch (PDOException $e) {
        die("Erreur lors de la création de la base de données: " . $e->getMessage());
    }
}

// Créer la base de données au chargement du fichier
createDatabase();

?>

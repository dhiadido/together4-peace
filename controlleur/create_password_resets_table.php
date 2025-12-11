<?php
// Script pour créer automatiquement la table password_resets
// Accédez à ce fichier via navigateur : http://localhost/dhia/controlleur/create_password_resets_table.php

require_once '../config/Database.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de la table password_resets</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Création de la table password_resets</h1>
        
        <?php
        try {
            $database = new Database();
            $db = $database->getConnection();
            
            // Vérifier si la table existe déjà
            $checkTable = $db->query("SHOW TABLES LIKE 'password_resets'");
            $tableExists = $checkTable->rowCount() > 0;
            
            if ($tableExists) {
                echo '<div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin-bottom: 20px;">';
                echo '<strong>⚠️ La table existe déjà.</strong> Voulez-vous la recréer ?';
                echo '<br><small>Si vous continuez, les données existantes seront perdues.</small>';
                echo '</div>';
                
                if (isset($_GET['force']) && $_GET['force'] === '1') {
                    // Supprimer la table existante
                    $db->exec("DROP TABLE IF EXISTS `password_resets`");
                    echo '<p style="color: #856404;">Table supprimée. Recréation en cours...</p>';
                } else {
                    echo '<p><a href="?force=1" style="background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Recréer la table (supprime les données)</a></p>';
                    echo '<p><a href="../views/login.php">← Retour à la page de connexion</a></p>';
                    exit;
                }
            }
            
            // SQL pour créer la table
            $sql = "CREATE TABLE `password_resets` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `email` VARCHAR(255) NOT NULL,
              `code` VARCHAR(6) NOT NULL,
              `expires_at` DATETIME NOT NULL,
              `used` TINYINT(1) NOT NULL DEFAULT 0,
              `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              INDEX `idx_email` (`email`),
              INDEX `idx_code` (`code`),
              INDEX `idx_expires_at` (`expires_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $db->exec($sql);
            
            echo '<div class="success">';
            echo '<h2>✅ Table créée avec succès !</h2>';
            echo '<p>La table <strong>password_resets</strong> a été créée dans la base de données <strong>baseuser</strong>.</p>';
            echo '</div>';
            
            // Vérifier la structure de la table
            $stmt = $db->query("DESCRIBE password_resets");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo '<h3>Structure de la table :</h3>';
            echo '<pre>';
            echo "Colonnes créées :\n";
            foreach ($columns as $column) {
                echo "- {$column['Field']} ({$column['Type']})\n";
            }
            echo '</pre>';
            
            echo '<p><a href="../views/login.php">← Retour à la page de connexion</a></p>';
            
        } catch (PDOException $e) {
            echo '<div class="error">';
            echo '<h2>❌ Erreur lors de la création de la table</h2>';
            echo '<p><strong>Erreur :</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
            
            echo '<h3>Solution alternative :</h3>';
            echo '<p>Exécutez manuellement le script SQL <code>create_password_resets_table.sql</code> dans phpMyAdmin.</p>';
        }
        ?>
    </div>
</body>
</html>


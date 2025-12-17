<?php
// Script de test pour afficher l'URI de redirection générée
// Utilisez ce script pour vérifier l'URI et l'ajouter dans Google Cloud Console

require_once '../config/GoogleConfig.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test URI de redirection Google OAuth</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #4285f4;
            padding-bottom: 10px;
        }
        .uri-box {
            background: #f8f9fa;
            border: 2px solid #4285f4;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            word-break: break-all;
            font-family: monospace;
            font-size: 14px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .success {
            background: #d4edda;
            border: 1px solid #28a745;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info {
            background: #d1ecf1;
            border: 1px solid #17a2b8;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Test URI de redirection Google OAuth</h1>
        
        <div class="info">
            <strong>ℹ️ Instructions :</strong><br>
            Copiez l'URI ci-dessous et ajoutez-la dans votre Google Cloud Console dans la section "Authorized redirect URIs" de votre OAuth 2.0 Client ID.
        </div>

        <h2>URI de redirection générée :</h2>
        <div class="uri-box">
            <?php 
            $redirectUri = GoogleConfig::getRedirectUri();
            echo htmlspecialchars($redirectUri); 
            ?>
        </div>

        <h2>URI alternative (méthode simple) :</h2>
        <div class="uri-box">
            <?php 
            $redirectUriSimple = GoogleConfig::getRedirectUriSimple();
            echo htmlspecialchars($redirectUriSimple); 
            ?>
        </div>

        <div class="warning">
            <strong>⚠️ Important :</strong><br>
            <ul>
                <li>L'URI doit correspondre <strong>exactement</strong> à celle configurée dans Google Cloud Console</li>
                <li>Vérifiez que le protocole (http/https) correspond</li>
                <li>Vérifiez que le domaine/host correspond</li>
                <li>Vérifiez que le chemin complet correspond</li>
                <li>Si votre projet est dans un sous-dossier, assurez-vous de l'inclure dans l'URI</li>
            </ul>
        </div>

        <h2>Informations du serveur :</h2>
        <div class="uri-box">
            <strong>HTTP_HOST:</strong> <?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'N/A'); ?><br>
            <strong>SCRIPT_NAME:</strong> <?php echo htmlspecialchars($_SERVER['SCRIPT_NAME'] ?? 'N/A'); ?><br>
            <strong>REQUEST_URI:</strong> <?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A'); ?><br>
            <strong>DOCUMENT_ROOT:</strong> <?php echo htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? 'N/A'); ?><br>
            <strong>HTTPS:</strong> <?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'Oui' : 'Non'; ?><br>
        </div>

        <div class="success">
            <strong>✅ Étapes suivantes :</strong><br>
            <ol>
                <li>Allez sur <a href="https://console.cloud.google.com/apis/credentials" target="_blank">Google Cloud Console - Credentials</a></li>
                <li>Sélectionnez votre OAuth 2.0 Client ID</li>
                <li>Dans "Authorized redirect URIs", cliquez sur "ADD URI"</li>
                <li>Copiez-collez l'URI générée ci-dessus (utilisez la première si elle fonctionne, sinon essayez la deuxième)</li>
                <li>Cliquez sur "SAVE"</li>
                <li>Attendez quelques minutes pour que les changements prennent effet</li>
                <li>Testez à nouveau la connexion Google</li>
            </ol>
        </div>

        <p style="margin-top: 30px;">
            <a href="../views/login.php">← Retour à la page de connexion</a>
        </p>
    </div>
</body>
</html>


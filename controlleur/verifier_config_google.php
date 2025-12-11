<?php
// Script de vérification de la configuration Google OAuth
require_once '../config/GoogleConfig.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification Configuration Google OAuth</title>
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
            margin-bottom: 20px;
        }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .warning { color: #ffc107; }
        .info { color: #17a2b8; }
        h1 { color: #333; }
        h2 { color: #666; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .check-item {
            margin: 10px 0;
            padding: 10px;
            background: #f8f9fa;
            border-left: 4px solid #ddd;
        }
        .check-item.success { border-left-color: #28a745; }
        .check-item.error { border-left-color: #dc3545; }
        .check-item.warning { border-left-color: #ffc107; }
    </style>
</head>
<body>
    <h1>🔍 Vérification de la Configuration Google OAuth</h1>

    <div class="card">
        <h2>1. Vérification du Client ID</h2>
        <?php
        $clientId = GoogleConfig::CLIENT_ID;
        $isRecaptcha = (strpos($clientId, '6L') === 0 && strpos($clientId, '.apps.googleusercontent.com') === false);
        $isOAuth = strpos($clientId, '.apps.googleusercontent.com') !== false;
        
        if ($isRecaptcha) {
            echo '<div class="check-item error">';
            echo '<strong>❌ ERREUR :</strong> Le Client ID semble être une clé <strong>reCAPTCHA</strong>, pas un Client ID OAuth.<br>';
            echo '<code>' . htmlspecialchars($clientId) . '</code><br><br>';
            echo 'Les Client IDs OAuth Google ont le format : <code>xxxxx-xxxxx.apps.googleusercontent.com</code><br>';
            echo 'Vous devez créer un vrai Client ID OAuth 2.0 dans Google Cloud Console.<br>';
            echo 'Consultez le fichier <strong>CREER_CLIENT_ID_GOOGLE.md</strong> pour les instructions.';
            echo '</div>';
        } elseif ($isOAuth) {
            echo '<div class="check-item success">';
            echo '<strong>✅ CORRECT :</strong> Le Client ID a le bon format OAuth.<br>';
            echo '<code>' . htmlspecialchars($clientId) . '</code>';
            echo '</div>';
        } else {
            echo '<div class="check-item warning">';
            echo '<strong>⚠️ ATTENTION :</strong> Le format du Client ID n\'est pas reconnu.<br>';
            echo '<code>' . htmlspecialchars($clientId) . '</code><br><br>';
            echo 'Format attendu : <code>xxxxx-xxxxx.apps.googleusercontent.com</code>';
            echo '</div>';
        }
        ?>
    </div>

    <div class="card">
        <h2>2. Vérification du Client Secret</h2>
        <?php
        $clientSecret = GoogleConfig::CLIENT_SECRET;
        $secretValid = !empty($clientSecret) && strlen($clientSecret) > 10;
        
        if ($secretValid) {
            echo '<div class="check-item success">';
            echo '<strong>✅ Client Secret présent</strong><br>';
            echo '<code>' . substr($clientSecret, 0, 10) . '...</code> (masqué pour sécurité)';
            echo '</div>';
        } else {
            echo '<div class="check-item error">';
            echo '<strong>❌ ERREUR :</strong> Client Secret manquant ou invalide.';
            echo '</div>';
        }
        ?>
    </div>

    <div class="card">
        <h2>3. URI de Redirection</h2>
        <?php
        $redirectUri = GoogleConfig::getRedirectUri();
        echo '<div class="check-item info">';
        echo '<strong>📍 URI de redirection générée :</strong><br>';
        echo '<code style="word-break: break-all; display: block; margin-top: 10px; padding: 10px; background: #e9ecef;">';
        echo htmlspecialchars($redirectUri);
        echo '</code><br><br>';
        echo '<strong>⚠️ IMPORTANT :</strong> Cette URI doit être ajoutée EXACTEMENT (caractère par caractère) dans Google Cloud Console :<br>';
        echo '<ol>';
        echo '<li>Allez dans Google Cloud Console > APIs & Services > Identifiants</li>';
        echo '<li>Sélectionnez votre ID client OAuth 2.0</li>';
        echo '<li>Dans "URIs de redirection autorisés", ajoutez l\'URI ci-dessus</li>';
        echo '<li>Assurez-vous qu\'elle correspond EXACTEMENT (même protocole http/https)</li>';
        echo '</ol>';
        echo '</div>';
        ?>
    </div>

    <div class="card">
        <h2>4. Informations du Serveur</h2>
        <div class="check-item">
            <strong>HTTP_HOST:</strong> <code><?php echo htmlspecialchars($_SERVER['HTTP_HOST']); ?></code><br>
            <strong>DOCUMENT_ROOT:</strong> <code><?php echo htmlspecialchars($_SERVER['DOCUMENT_ROOT']); ?></code><br>
            <strong>HTTPS:</strong> <code><?php echo isset($_SERVER['HTTPS']) ? htmlspecialchars($_SERVER['HTTPS']) : 'non défini'; ?></code><br>
            <strong>Protocole détecté:</strong> <code><?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http'; ?></code>
        </div>
    </div>

    <div class="card">
        <h2>5. Prochaines Étapes</h2>
        <?php if ($isRecaptcha): ?>
            <div class="check-item error">
                <strong>🔴 ACTION REQUISE :</strong>
                <ol>
                    <li>Créez un vrai Client ID OAuth 2.0 dans Google Cloud Console</li>
                    <li>Suivez les instructions dans <strong>CREER_CLIENT_ID_GOOGLE.md</strong></li>
                    <li>Mettez à jour <code>config/GoogleConfig.php</code> avec le nouveau Client ID et Secret</li>
                    <li>Ajoutez l'URI de redirection dans Google Cloud Console</li>
                    <li>Rechargez cette page pour vérifier</li>
                </ol>
            </div>
        <?php else: ?>
            <div class="check-item success">
                <strong>✅ Configuration apparemment correcte</strong>
                <ol>
                    <li>Vérifiez que l'URI de redirection est bien configurée dans Google Cloud Console</li>
                    <li>Testez la connexion depuis la page de connexion</li>
                    <li>Si vous obtenez encore des erreurs, consultez <strong>TROUBLESHOOTING_GOOGLE_OAUTH.md</strong></li>
                </ol>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>📚 Documentation</h2>
        <ul>
            <li><a href="../CREER_CLIENT_ID_GOOGLE.md" target="_blank">CREER_CLIENT_ID_GOOGLE.md</a> - Guide pour créer un Client ID OAuth</li>
            <li><a href="../TROUBLESHOOTING_GOOGLE_OAUTH.md" target="_blank">TROUBLESHOOTING_GOOGLE_OAUTH.md</a> - Guide de dépannage</li>
            <li><a href="../GOOGLE_OAUTH_SETUP.md" target="_blank">GOOGLE_OAUTH_SETUP.md</a> - Configuration générale</li>
        </ul>
    </div>
</body>
</html>


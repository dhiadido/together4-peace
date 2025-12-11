<?php
// config/GoogleConfig.example.php
// Fichier d'exemple - Copiez ce fichier vers GoogleConfig.php et remplissez vos valeurs

class GoogleConfig {
    // ⚠️ REMPLACEZ PAR VOTRE VRAI CLIENT ID OAuth 2.0
    // Format attendu : xxxxx-xxxxx.apps.googleusercontent.com
    // ❌ NE PAS utiliser une clé reCAPTCHA (format : 6LeTwCcsAAAAA...)
    const CLIENT_ID = 'VOTRE_CLIENT_ID.apps.googleusercontent.com';
    
    // ⚠️ REMPLACEZ PAR VOTRE VRAI CLIENT SECRET
    // Format attendu : GOCSPX-xxxxxxxxxxxxx
    const CLIENT_SECRET = 'VOTRE_CLIENT_SECRET';
    
    // URL de redirection après authentification Google
    public static function getRedirectUri() {
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        
        // Obtenir le chemin du script actuel
        $scriptPath = $_SERVER['SCRIPT_NAME'];
        
        // Si on est dans google_auth.php, on remplace par google_callback.php
        // Sinon, on construit le chemin depuis le document root
        if (strpos($scriptPath, 'google_auth.php') !== false) {
            $basePath = str_replace('google_auth.php', 'google_callback.php', $scriptPath);
        } else {
            // Construction depuis le document root
            $documentRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
            $currentFile = str_replace('\\', '/', __FILE__);
            $projectPath = str_replace($documentRoot, '', dirname(dirname($currentFile)));
            $basePath = $projectPath . '/controlleur/google_callback.php';
        }
        
        // Nettoyer le chemin (supprimer les doubles slashes sauf après le protocole)
        $basePath = preg_replace('#/+#', '/', $basePath);
        
        return $protocol . '://' . $host . $basePath;
    }
}
?>


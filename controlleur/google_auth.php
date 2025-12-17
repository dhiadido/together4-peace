<?php
// controlleur/google_auth.php
// Ce fichier initie le processus d'authentification Google

session_start();

require_once '../vendor/autoload.php';
require_once '../config/GoogleConfig.php';

use League\OAuth2\Client\Provider\Google;

try {
    // Essayer d'abord la méthode principale, sinon utiliser la méthode simple
    try {
        $redirectUri = GoogleConfig::getRedirectUri();
    } catch (Exception $e) {
        $redirectUri = GoogleConfig::getRedirectUriSimple();
    }
    
    // Log pour débogage (peut être commenté en production)
    error_log('Google OAuth Redirect URI: ' . $redirectUri);
    
    // Configuration du provider Google
    $provider = new Google([
        'clientId'     => GoogleConfig::CLIENT_ID,
        'clientSecret' => GoogleConfig::CLIENT_SECRET,
        'redirectUri'  => $redirectUri,
    ]);

    // Scopes demandés à Google (format correct pour OAuth2)
    // Utilisation des scopes recommandés par Google
    $options = [
        'scope' => [
            'openid',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile'
        ],
        'access_type' => 'online',
        'prompt' => 'consent'
    ];

    // Obtenir l'URL d'autorisation
    $authUrl = $provider->getAuthorizationUrl($options);

    // Stocker le state pour la vérification CSRF
    $_SESSION['oauth2state'] = $provider->getState();
    
    // Stocker aussi l'URI utilisée pour le débogage
    $_SESSION['oauth2_redirect_uri'] = $redirectUri;

    // Rediriger vers Google
    header('Location: ' . $authUrl);
    exit;

} catch (Exception $e) {
    // Log de l'erreur pour le débogage
    error_log('Google OAuth Error: ' . $e->getMessage());
    $_SESSION['error'] = 'Erreur lors de l\'initialisation de l\'authentification Google : ' . $e->getMessage() . 
                        '<br><small>Vérifiez que l\'URI de redirection est correctement configurée dans Google Cloud Console.</small>';
    header("Location: ../views/login.php");
    exit;
}
?>


<?php
// controlleur/google_callback.php
session_start();

require_once '../vendor/autoload.php';
require_once '../config/Database.php';
require_once '../config/GoogleConfig.php';
require_once '../models/user.php';

use League\OAuth2\Client\Provider\Google;

try {
    // Utiliser la même URI que celle utilisée lors de l'authentification
    if (isset($_SESSION['oauth2_redirect_uri'])) {
        $redirectUri = $_SESSION['oauth2_redirect_uri'];
    } else {
        try {
            $redirectUri = GoogleConfig::getRedirectUri();
        } catch (Exception $e) {
            $redirectUri = GoogleConfig::getRedirectUriSimple();
        }
    }
    
    // Configuration du provider Google
    $provider = new Google([
        'clientId'     => GoogleConfig::CLIENT_ID,
        'clientSecret' => GoogleConfig::CLIENT_SECRET,
        'redirectUri'  => $redirectUri,
    ]);

    // Vérifier si on a un code d'autorisation
    if (!isset($_GET['code'])) {
        // Si pas de code, rediriger vers la page de connexion
        $_SESSION['error'] = 'Erreur lors de l\'authentification Google.';
        header("Location: ../views/login.php");
        exit;
    }

    // Vérifier le state pour la sécurité CSRF
    if (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {
        if (isset($_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
        }
        $_SESSION['error'] = 'Erreur de sécurité lors de l\'authentification.';
        header("Location: ../views/login.php");
        exit;
    }

    // Échanger le code contre un token d'accès
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Obtenir les informations de l'utilisateur Google
    $userInfo = $provider->getResourceOwner($token);
    
    $googleId = $userInfo->getId();
    $email = trim($userInfo->getEmail() ?? '');
    $firstName = trim($userInfo->getFirstName() ?? '');
    $lastName = trim($userInfo->getLastName() ?? '');
    $picture = $userInfo->getAvatar();

    // Validation de l'email
    if (empty($email)) {
        $_SESSION['error'] = 'Impossible de récupérer l\'adresse email depuis votre compte Google. Veuillez vérifier les permissions de votre compte Google.';
        header("Location: ../views/login.php");
        exit;
    }

    // Vérifier que l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'L\'adresse email récupérée depuis Google n\'est pas valide.';
        header("Location: ../views/login.php");
        exit;
    }

    // Vérifier qu'il n'y a pas d'espaces dans l'email (sécurité)
    if (strpos($email, ' ') !== false) {
        $email = str_replace(' ', '', $email);
    }

    // Connexion à la base de données
    $database = new Database();
    $db = $database->getConnection();
    $userModel = new User($db);

    // Vérifier si l'utilisateur existe déjà (par email ou google_id)
    $existingUser = $userModel->findByEmailOrGoogleId($email, $googleId);

    if ($existingUser) {
        // Utilisateur existant - connexion
        $_SESSION['user_id'] = $existingUser['id_utilisateur'];
        $_SESSION['user_nom'] = $existingUser['nom'];
        $_SESSION['user_prenom'] = $existingUser['prenom'] ?? '';
        $_SESSION['user_email'] = $existingUser['email'];
        $_SESSION['user_role'] = $existingUser['role'] ?? 'Membre';
        $_SESSION['user_photo'] = $existingUser['photo'] ?? $picture ?? 'default-avatar.jpg';
        $_SESSION['date_inscription'] = $existingUser['date_inscription'] ?? date('Y-m-d');

        // Mettre à jour le google_id si nécessaire
        if (empty($existingUser['google_id'])) {
            $userModel->updateGoogleId($existingUser['id_utilisateur'], $googleId);
        }

        // Nettoyer le state
        if (isset($_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
        }

        header("Location: ../views/dashboard.php");
        exit;
    } else {
        // Nouvel utilisateur - inscription automatique
        $nom = $lastName ?? 'Utilisateur';
        $prenom = $firstName ?? '';
        
        $result = $userModel->registerWithGoogle($nom, $prenom, $email, $googleId, $picture);

        if (is_array($result)) {
            // Inscription réussie
            $_SESSION['user_id'] = $result['id_utilisateur'];
            $_SESSION['user_nom'] = $result['nom'];
            $_SESSION['user_prenom'] = $result['prenom'] ?? '';
            $_SESSION['user_email'] = $result['email'];
            $_SESSION['user_role'] = $result['role'] ?? 'Membre';
            $_SESSION['user_photo'] = $result['photo'] ?? $picture ?? 'default-avatar.jpg';
            $_SESSION['date_inscription'] = $result['date_inscription'] ?? date('Y-m-d');
            $_SESSION['success'] = 'Inscription réussie avec Google !';

            // Nettoyer le state
            if (isset($_SESSION['oauth2state'])) {
                unset($_SESSION['oauth2state']);
            }

            header("Location: ../views/dashboard.php");
            exit;
        } else {
            // Erreur lors de l'inscription
            $_SESSION['error'] = $result;
            header("Location: ../views/login.php");
            exit;
        }
    }

} catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
    // Erreur spécifique du provider OAuth
    $errorMessage = $e->getMessage();
    $errorCode = $e->getCode();
    
    error_log('Google OAuth IdentityProviderException: ' . $errorMessage . ' (Code: ' . $errorCode . ')');
    
    $_SESSION['error'] = 'Erreur lors de l\'authentification Google : ' . $errorMessage . 
                        '<br><small>Code d\'erreur: ' . $errorCode . '</small>';
    header("Location: ../views/login.php");
    exit;
} catch (Exception $e) {
    // Gestion des erreurs générales
    error_log('Google OAuth Error: ' . $e->getMessage());
    $_SESSION['error'] = 'Erreur lors de l\'authentification Google : ' . $e->getMessage();
    header("Location: ../views/login.php");
    exit;
}
?>


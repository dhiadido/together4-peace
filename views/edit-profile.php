<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/Database.php';
require_once '../models/user.php';

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

// Récupérer les informations de l'utilisateur
$user = $userModel->getUserById($_SESSION['user_id']);

if (!$user) {
    $_SESSION['error'] = 'Utilisateur non trouvé.';
    header("Location: dashboard.php");
    exit;
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error']);
unset($_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon profil - Together4Peace</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .auth-page {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .auth-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .auth-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 40px;
            width: 100%;
            max-width: 600px;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-logo {
            height: 60px;
            margin-bottom: 20px;
        }

        .auth-header h2 {
            color: var(--color-primary);
            margin-bottom: 10px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px 15px;
            transition: all 0.3s;
            width: 100%;
            margin-bottom: 15px;
        }

        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 45, 98, 0.25);
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            color: var(--color-dark);
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--color-primary);
            border: none;
            padding: 12px;
            font-size: 1.1em;
            width: 100%;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #001a3d;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            padding: 12px;
            font-size: 1.1em;
            width: 100%;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            margin-top: 10px;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95em;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .preview-container {
            margin-top: 10px;
            text-align: center;
        }

        .img-preview {
            max-width: 150px;
            max-height: 150px;
            border-radius: 50%;
            border: 3px solid var(--color-light);
        }

        .current-photo {
            margin-bottom: 15px;
            text-align: center;
        }

        .current-photo img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 3px solid var(--color-primary);
            object-fit: cover;
        }

        .password-info {
            font-size: 0.875rem;
            color: #666;
            margin-top: -10px;
            margin-bottom: 15px;
        }

        .password-match {
            font-size: 0.875rem;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .password-match.success {
            color: #28a745;
        }

        .password-match.error {
            color: #dc3545;
        }
    </style>
</head>
<body class="auth-page">
    <header>
        <a href="index.html" class="logo-link">
            <div class="logo">
                <img src="../logo.png" alt="Logo Together4Peace" class="header-logo">
                <span class="site-name">Together4Peace</span>
            </div>
        </a>
        <nav>
            <ul>
                <li><a href="index.html">Accueil</a></li>
                <li><a href="dashboard.php" class="nav-cta active">Mon Profil</a></li>
                <li><a href="logout.php" class="nav-cta">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <section class="auth-section">
        <div class="auth-container">
            <form class="auth-form" id="editProfileForm" action="../controlleur/edit_profile_traitement.php" method="POST" enctype="multipart/form-data">
                <div class="auth-header">
                    <img src="../logo.png" alt="Logo Together4Peace" class="auth-logo">
                    <h2>Modifier mon profil</h2>
                    <p>Mettez à jour vos informations personnelles</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <div class="current-photo">
                    <img src="../uploads/<?php echo htmlspecialchars($user['photo'] ?? 'default-avatar.jpg'); ?>" 
                         alt="Photo actuelle" 
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgdmlld0JveD0iMCAwIDEyMCAxMjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMjAiIGhlaWdodD0iMTIwIiByeD0iNjAiIGZpbGw9IiNEOERDREIiLz4KPHBhdGggZD0iTTQ1IDQ1QzQ1IDM4LjkyNDkgNTAuMDI0OSAzNCA1NiAzNEM2MS45NzUxIDM0IDY3IDM4LjkyNDkgNjcgNDVDNjcgNTEuMDc1MSA2MS45NzUxIDU2IDU2IDU2QzUwLjAyNDkgNTYgNDUgNTEuMDc1MSA0NSA0NVoiIGZpbGw9IiM5OTk5OTkiLz4KPHBhdGggZD0iTTM2IDc0QzM2IDY3LjkyNDkgNDQuOTI0OSA2MiA1NiA2MkM2Ny4wNzUxIDYyIDc2IDY3LjkyNDkgNzYgNzRWNzZDMzYgNzYgMzYgNzYgMzYgNzRaIiBmaWxsPSIjOTk5OTk5Ii8+Cjwvc3ZnPgo='">
                    <p><small>Photo actuelle</small></p>
                </div>

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom *</label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                           value="<?php echo htmlspecialchars($user['nom'] ?? ''); ?>" 
                           placeholder="Votre nom" required>
                </div>

                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" 
                           value="<?php echo htmlspecialchars($user['prenom'] ?? ''); ?>" 
                           placeholder="Votre prénom">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail *</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" 
                           placeholder="exemple@mail.com" required>
                </div>

                <div class="mb-3">
                    <label for="photo" class="form-label">Photo de profil (optionnel)</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    <div id="preview" class="preview-container"></div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Nouveau mot de passe (optionnel)</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Laissez vide pour ne pas changer">
                    <div class="password-info">
                        <i class="fas fa-info-circle"></i> Laissez ce champ vide si vous ne souhaitez pas changer votre mot de passe
                    </div>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                           placeholder="Confirmez le nouveau mot de passe">
                    <div id="matchMsg" class="password-match"></div>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </div>

                <div class="d-grid">
                    <a href="dashboard.php" class="btn-secondary" style="text-decoration: none; text-align: center;">
                        <i class="fas fa-arrow-left"></i> Retour au tableau de bord
                    </a>
                </div>
            </form>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <img src="../logo.png" alt="Logo Together4Peace" class="header-logo footer-logo-img">
                <span class="site-name">Together4Peace</span>
            </div>
            <div class="footer-links">
                <h4>Liens Utiles</h4>
                <ul>
                    <li><a href="about.html">Notre Mission</a></li>
                    <li><a href="charter.html">La Charte</a></li>
                    <li><a href="offers.html">Nos Offres</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="footer-social">
                <h4>Suivez-nous</h4>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2025 Together4Peace. Tous droits réservés. | Mentions Légales
        </div>
    </footer>

    <script src="../assets/edit-profile.js"></script>
</body>
</html>


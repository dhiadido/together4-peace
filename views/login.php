<?php
session_start();
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
    <title>Connexion - Together4Peace</title>
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
            max-width: 450px;
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
        }

        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 45, 98, 0.25);
        }

        .btn-primary {
            background-color: var(--color-primary);
            border: none;
            padding: 12px;
            font-size: 1.1em;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #001a3d;
        }

        .auth-link-text {
            margin: 0;
        }

        .auth-link {
            color: var(--color-primary);
            font-weight: 600;
        }

        .auth-link:hover {
            text-decoration: underline;
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

        .g-recaptcha {
            display: flex;
            justify-content: center;
            margin: 15px 0;
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
                <li><a href="about.html">À Propos</a></li>
                <li><a href="quiz.html">Quiz</a></li>
                <li><a href="testimonials.html">Témoignages</a></li>
                <li><a href="charter.html">Charte</a></li>
                <li><a href="offers.html" class="nav-cta">Offres</a></li>
                <li><a href="register.html" class="nav-cta">S'inscrire</a></li>
                <li><a href="login.php" class="nav-cta active">Se connecter</a></li>
            </ul>
        </nav>
        <a href="admin-login.php" class="btn btn-donate">Espace Admin</a>
    </header>

    <section class="auth-section">
        <div class="auth-container">
            <form class="auth-form" id="loginForm" action="../controlleur/login_traiement.php" method="POST">
                <div class="auth-header">
                    <img src="../logo.png" alt="Logo Together4Peace" class="auth-logo">
                    <h2>Connexion</h2>
                    <p>Accédez à votre espace personnel</p>
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

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="exemple@mail.com" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" required>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="showPassword">
                    <label class="form-check-label" for="showPassword">Afficher le mot de passe</label>
                </div>
                
                <div class="text-center mb-3">
                    <a href="forgot-password.php" style="color: var(--color-primary); text-decoration: none;">
                        🔐 Mot de passe oublié ?
                    </a>
                </div>

                <div class="mb-3">
                    <div class="g-recaptcha" data-sitekey="6LdFyCcsAAAAAEV6ZrchO9nWUb51m1W9BeSFPXD2"></div>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">Se connecter</button>
                </div>

                <div class="text-center mb-3" style="position: relative;">
                    <div style="display: flex; align-items: center; margin: 20px 0;">
                        <div style="flex: 1; height: 1px; background: #ddd;"></div>
                        <span style="padding: 0 15px; color: #666; font-size: 0.9em;">OU</span>
                        <div style="flex: 1; height: 1px; background: #ddd;"></div>
                    </div>
                    <a href="../controlleur/google_auth.php" class="btn btn-primary" style="background: #4285f4; border: none; padding: 12px 30px; border-radius: 8px; text-decoration: none; display: inline-block; color: white; font-weight: 500; margin-bottom: 10px; width: 100%;">
                        <i class="fab fa-google"></i> Se connecter avec Google
                    </a>
                    <div style="text-align: center; margin-top: 10px;">
                        <a href="../controlleur/test_google_redirect_uri.php" style="font-size: 0.85em; color: #666; text-decoration: none;">
                            <i class="fas fa-info-circle"></i> Problème avec Google ? Vérifier l'URI de redirection
                        </a>
                    </div>
                    <a href="face_verify.php" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 30px; border-radius: 8px; text-decoration: none; display: inline-block; color: white; font-weight: 500; width: 100%;">
                        <i class="fas fa-user-check"></i> Connexion Face ID
                    </a>
                </div>

                <div class="text-center">
                    <p class="auth-link-text">
                        Pas encore de compte ?
                        <a href="register.html" class="auth-link">S'inscrire</a>
                    </p>
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

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="../assets/login.js"></script>
</body>
</html>
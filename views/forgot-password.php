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
    <title>Mot de passe oublié - Together4Peace</title>
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
            width: 100%;
            margin-bottom: 15px;
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
        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: var(--color-primary);
            text-decoration: none;
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
    </header>

    <section class="auth-section">
        <div class="auth-container">
            <div class="auth-header">
                <img src="../logo.png" alt="Logo Together4Peace" class="auth-logo">
                <h2>🔐 Mot de passe oublié</h2>
                <p>Entrez votre email pour recevoir un code de vérification</p>
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

            <form action="../controlleur/forgot_password_traitement.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="votre-email@exemple.com" required>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane"></i> Envoyer le code
                </button>
            </form>

            <div class="back-link">
                <a href="login.php"><i class="fas fa-arrow-left"></i> Retour à la connexion</a>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <img src="../logo.png" alt="Logo Together4Peace" class="header-logo footer-logo-img">
                <span class="site-name">Together4Peace</span>
            </div>
            <div class="footer-bottom">
                &copy; 2025 Together4Peace. Tous droits réservés.
            </div>
        </div>
    </footer>
    <script src="../assets/forgot-password.js"></script>
</body>
</html>
<?php
session_start();

if (!isset($_SESSION['reset_verified'])) {
    header("Location: forgot-password.php");
    exit;
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe - Together4Peace</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .auth-page {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
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
        .auth-header h2 {
            color: var(--color-primary);
            margin-bottom: 10px;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .form-control:focus {
            border-color: var(--color-primary);
            outline: none;
        }
        .btn-primary {
            width: 100%;
            padding: 12px;
            background: var(--color-primary);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
        }
        .btn-primary:hover {
            background-color: #001a3d;
        }
        .password-strength {
            height: 5px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 3px;
            background: #ddd;
            transition: all 0.3s;
        }
        .password-strength.weak { background: #dc3545; width: 33%; }
        .password-strength.medium { background: #ffc107; width: 66%; }
        .password-strength.strong { background: #28a745; width: 100%; }
        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #f8d7da;
            color: #721c24;
        }
        .password-match {
            font-size: 0.875rem;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        .password-match.success { color: #28a745; }
        .password-match.error { color: #dc3545; }
        .form-label {
            display: block;
            margin-bottom: 5px;
            color: var(--color-dark);
            font-weight: 500;
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h2>🔑 Nouveau mot de passe</h2>
            <p>Choisissez un nouveau mot de passe sécurisé</p>
        </div>

        <?php if ($error): ?>
            <div class="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="../controlleur/reset_password_traitement.php" method="POST" id="resetForm">
            <label class="form-label">Nouveau mot de passe</label>
            <input type="password" class="form-control" name="password" id="password" 
                   placeholder="Minimum 6 caractères" required>
            <div class="password-strength" id="strength"></div>

            <label class="form-label">Confirmer le mot de passe</label>
            <input type="password" class="form-control" name="confirm" id="confirm" 
                   placeholder="Retapez votre mot de passe" required>
            <div id="matchMsg" class="password-match"></div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-lock"></i> Réinitialiser le mot de passe
            </button>
        </form>
    </div>

    <script src="../assets/reset-password.js"></script>
</body>
</html>

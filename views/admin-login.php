<?php
session_start();
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Connexion</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .auth-page { background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .auth-container { background: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); padding: 40px; width: 100%; max-width: 420px; }
        .auth-header { text-align: center; margin-bottom: 24px; }
        .auth-header h2 { color: var(--color-primary); margin-bottom: 8px; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 12px; }
        .btn-primary { width: 100%; padding: 12px; background: var(--color-primary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .btn-primary:hover { background: #001a3d; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; background: #f8d7da; color: #721c24; }
    </style>
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <img src="../logo.png" alt="Logo Together4Peace" style="height:60px; margin-bottom:10px;">
            <h2>Espace Admin</h2>
            <p>Connexion sécurisée</p>
        </div>

        <?php if ($error): ?>
            <div class="alert"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="../controlleur/admin_login_traitement.php" method="POST">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="admin@exemple.com" required>

            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Votre mot de passe" required>

            <button type="submit" class="btn-primary">Se connecter</button>
        </form>
    </div>
</body>
</html>


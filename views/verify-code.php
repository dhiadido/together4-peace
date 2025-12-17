<?php
session_start();

if (!isset($_SESSION['reset_email'])) {
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
    <title>Vérification du code - Together4Peace</title>
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
        .auth-header h2 {
            color: var(--color-primary);
            margin-bottom: 10px;
        }
        .code-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }
        .code-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #ddd;
            border-radius: 8px;
        }
        .code-input:focus {
            border-color: var(--color-primary);
            outline: none;
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
            margin-top: 20px;
        }
        .btn-primary:hover {
            background-color: #001a3d;
        }
        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #f8d7da;
            color: #721c24;
        }
        .resend-link {
            text-align: center;
            margin-top: 20px;
        }
        .resend-link a {
            color: var(--color-primary);
            text-decoration: none;
        }
    </style>
</head>
<body class="auth-page">
    <section class="auth-section">
        <div class="auth-container">
            <div class="auth-header">
                <h2>✉️ Code de vérification</h2>
                <p>Entrez le code à 6 chiffres envoyé à :<br><strong><?php echo htmlspecialchars($_SESSION['reset_email']); ?></strong></p>
            </div>

            <?php if ($error): ?>
                <div class="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="../controlleur/verify_code_traitement.php" method="POST" id="codeForm">
                <div class="code-inputs">
                    <input type="text" class="code-input" maxlength="1" name="digit1" required autofocus>
                    <input type="text" class="code-input" maxlength="1" name="digit2" required>
                    <input type="text" class="code-input" maxlength="1" name="digit3" required>
                    <input type="text" class="code-input" maxlength="1" name="digit4" required>
                    <input type="text" class="code-input" maxlength="1" name="digit5" required>
                    <input type="text" class="code-input" maxlength="1" name="digit6" required>
                </div>
                <input type="hidden" name="code" id="fullCode">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check"></i> Vérifier le code
                </button>
            </form>

            <div class="resend-link">
                <a href="../controlleur/forgot_password_traitement.php?resend=1">
                    <i class="fas fa-redo"></i> Renvoyer le code
                </a>
            </div>
        </div>
    </section>

    <script src="../assets/verify-code.js"></script>
</body>
</html>
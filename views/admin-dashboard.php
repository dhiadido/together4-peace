<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin-login.php');
    exit;
}

require_once '../config/Database.php';
require_once '../models/user.php';

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

$users = $userModel->getAllUsers();
$totalUsers = $userModel->countUsers();
$totalAdmins = $userModel->countByRole('admin');
$recentUsers = $userModel->countRecent(7);

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Together4Peace</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .admin-header-section {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .admin-header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.1);
        }

        .admin-header-content {
            position: relative;
            z-index: 2;
        }

        .admin-header-content h1 {
            margin: 0 0 10px 0;
            font-size: 2.2em;
        }

        .admin-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 2.5em;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: var(--color-primary);
            display: block;
        }

        .stat-label {
            color: var(--color-dark);
            font-size: 0.9em;
        }

        .action-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            border-left: 4px solid var(--color-primary);
        }

        .action-card h3 {
            color: var(--color-primary);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px 15px;
            transition: all 0.3s;
            font-size: 1em;
        }

        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 45, 98, 0.25);
            outline: none;
        }

        .table-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: var(--color-light);
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--color-primary);
            border-bottom: 2px solid var(--color-primary);
        }

        .table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .table tr:hover {
            background: #f9f9f9;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }

        .badge-admin {
            background: #e1ecff;
            color: #0b5394;
        }

        .badge-user {
            background: #e9f7ef;
            color: #1c7c3b;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-dashboard {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-edit {
            background: var(--color-accent);
            color: white;
        }

        .btn-edit:hover {
            background: #1e8e7a;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .icon-blue { color: #4285f4; }
        .icon-green { color: #34a853; }
        .icon-yellow { color: #fbbc04; }
        .icon-primary { color: var(--color-primary); }
    </style>
</head>
<body>
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
                <li><a href="admin-dashboard.php" class="nav-cta active">Dashboard Admin</a></li>
                <li><a href="../controlleur/admin_logout.php" class="nav-cta">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <div class="dashboard-container">
        <div class="admin-header-section">
            <div class="admin-header-content">
                <h1><i class="fas fa-shield-alt"></i> Dashboard Administrateur</h1>
                <p>Gérez les utilisateurs et les comptes de la plateforme</p>
                <div class="admin-info">
                    <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['admin_email']); ?></span>
                    <a href="../controlleur/admin_logout.php" class="btn-dashboard" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white;">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                </div>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users stat-icon icon-blue"></i>
                <span class="stat-number"><?php echo $totalUsers; ?></span>
                <span class="stat-label">Total utilisateurs</span>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-shield stat-icon icon-primary"></i>
                <span class="stat-number"><?php echo $totalAdmins; ?></span>
                <span class="stat-label">Administrateurs</span>
            </div>
            <div class="stat-card">
                <i class="fas fa-calendar-check stat-icon icon-green"></i>
                <span class="stat-number"><?php echo $recentUsers; ?></span>
                <span class="stat-label">Inscriptions (7 jours)</span>
            </div>
        </div>

        <div class="action-card">
            <h3><i class="fas fa-user-plus"></i> Créer un nouveau compte</h3>
            <form action="../controlleur/admin_user_traitement.php" method="POST" class="form-grid" id="create-user-form">
                <input type="hidden" name="action" value="create">
                <input type="text" name="nom" id="create-nom" class="form-control" placeholder="Nom *" required>
                <input type="text" name="prenom" id="create-prenom" class="form-control" placeholder="Prénom">
                <input type="email" name="email" id="create-email" class="form-control" placeholder="Email *" required>
                <input type="password" name="password" id="create-password" class="form-control" placeholder="Mot de passe *" required>
                <select name="role" id="create-role" class="form-control">
                    <option value="user">Utilisateur</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" class="btn-dashboard" style="background: var(--color-primary); color: white;">
                    <i class="fas fa-plus"></i> Créer le compte
                </button>
            </form>
        </div>

        <div class="action-card">
            <h3><i class="fas fa-list"></i> Liste des utilisateurs</h3>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users) === 0): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                                    <i class="fas fa-inbox" style="font-size: 3em; margin-bottom: 10px; display: block;"></i>
                                    Aucun utilisateur enregistré.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars(trim($user['nom'] . ' ' . $user['prenom'])); ?></strong></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <?php if (strtolower($user['role']) === 'admin'): ?>
                                            <span class="badge badge-admin"><i class="fas fa-shield-alt"></i> Admin</span>
                                        <?php else: ?>
                                            <span class="badge badge-user"><i class="fas fa-user"></i> Utilisateur</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if (isset($user['date_inscription']) && $user['date_inscription'] !== 'N/A') {
                                            echo htmlspecialchars(date('d/m/Y', strtotime($user['date_inscription'])));
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <button class="btn-dashboard btn-edit btn-edit-user"
                                                data-id="<?php echo $user['id_utilisateur']; ?>"
                                                data-nom="<?php echo htmlspecialchars($user['nom']); ?>"
                                                data-prenom="<?php echo htmlspecialchars($user['prenom']); ?>"
                                                data-email="<?php echo htmlspecialchars($user['email']); ?>"
                                                data-role="<?php echo htmlspecialchars($user['role']); ?>">
                                                <i class="fas fa-edit"></i> Modifier
                                            </button>
                                            <form action="../controlleur/admin_user_traitement.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');" style="display: inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $user['id_utilisateur']; ?>">
                                                <button type="submit" class="btn-dashboard btn-danger">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="action-card" id="edit-panel" style="display:none;">
            <h3><i class="fas fa-user-edit"></i> Modifier un compte</h3>
            <form action="../controlleur/admin_user_traitement.php" method="POST" class="form-grid" id="edit-user-form">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit-id">
                <input type="text" name="nom" id="edit-nom" class="form-control" placeholder="Nom *" required>
                <input type="text" name="prenom" id="edit-prenom" class="form-control" placeholder="Prénom">
                <input type="email" name="email" id="edit-email" class="form-control" placeholder="Email *" required>
                <input type="password" name="password" id="edit-password" class="form-control" placeholder="Nouveau mot de passe (optionnel)">
                <select name="role" id="edit-role" class="form-control">
                    <option value="user">Utilisateur</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" class="btn-dashboard" style="background: var(--color-primary); color: white;">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <button type="button" class="btn-dashboard" style="background: #6c757d; color: white;" onclick="document.getElementById('edit-panel').style.display='none';">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </form>
        </div>
    </div>

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

    <script src="../assets/admin.js"></script>
</body>
</html>

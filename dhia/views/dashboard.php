<?php
// views/dashboard.php
session_start();

// ✅ CORRECTION : Rediriger vers login.php au lieu de login.html
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Récupérer les informations utilisateur
$user_nom = htmlspecialchars($_SESSION['user_nom'] ?? 'Utilisateur');
$user_role = htmlspecialchars($_SESSION['user_role'] ?? 'Membre');
$user_email = htmlspecialchars($_SESSION['user_email'] ?? '');
$user_photo = $_SESSION['user_photo'] ?? 'default-avatar.jpg';
$date_inscription = $_SESSION['date_inscription'] ?? date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Together4Peace</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.1);
        }
        
        .profile-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 30px;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            background: #f0f0f0;
        }
        
        .profile-info h1 {
            margin: 0 0 10px 0;
            font-size: 2.2em;
        }
        
        .profile-role {
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            display: inline-block;
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
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .action-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-left: 4px solid var(--color-primary);
        }
        
        .action-card h3 {
            color: var(--color-primary);
            margin-bottom: 15px;
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
        }
        
        .btn-edit {
            background: var(--color-accent);
            color: white;
        }
        
        .btn-logout {
            background: #dc3545;
            color: white;
        }
        
        .user-details {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--color-light);
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .welcome-message {
            text-align: center;
            margin: 30px 0;
            font-size: 1.2em;
        }
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
                <li><a href="about.html">À Propos</a></li>
                <li><a href="quiz.html">Quiz</a></li>
                <li><a href="dashboard.php" class="nav-cta active">Mon Profil</a></li>
                <li><a href="logout.php" class="nav-cta">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <div class="dashboard-container">
        <div class="profile-header">
            <div class="profile-content">
                <div class="profile-avatar-container">
                    <img src="../uploads/<?php echo $user_photo; ?>" alt="Photo de profil" class="profile-avatar" 
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgdmlld0JveD0iMCAwIDEyMCAxMjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMjAiIGhlaWdodD0iMTIwIiByeD0iNjAiIGZpbGw9IiNEOERDREIiLz4KPHBhdGggZD0iTTQ1IDQ1QzQ1IDM4LjkyNDkgNTAuMDI0OSAzNCA1NiAzNEM2MS45NzUxIDM0IDY3IDM4LjkyNDkgNjcgNDVDNjcgNTEuMDc1MSA2MS45NzUxIDU2IDU2IDU2QzUwLjAyNDkgNTYgNDUgNTEuMDc1MSA0NSA0NVoiIGZpbGw9IiM5OTk5OTkiLz4KPHBhdGggZD0iTTM2IDc0QzM2IDY3LjkyNDkgNDQuOTI0OSA2MiA1NiA2MkM2Ny4wNzUxIDYyIDc2IDY3LjkyNDkgNzYgNzRWNzZDMzYgNzYgMzYgNzYgMzYgNzRaIiBmaWxsPSIjOTk5OTk5Ii8+Cjwvc3ZnPgo='">
                </div>
                <div class="profile-info">
                    <h1><?php echo $user_nom; ?></h1>
                    <span class="profile-role"><?php echo $user_role; ?></span>
                    <p class="mt-3">Bienvenue dans votre espace personnel dédié à la paix</p>
                </div>
            </div>
        </div>

        <div class="welcome-message">
            <h2>🎉 Félicitations ! Vous faites maintenant partie du mouvement Together4Peace</h2>
            <p>Votre engagement contribue à bâtir un monde plus pacifique et inclusif</p>
        </div>

        <div class="user-details">
            <h3><i class="fas fa-info-circle"></i> Informations personnelles</h3>
            <div class="detail-item">
                <span><strong>Nom d'utilisateur :</strong></span>
                <span><?php echo $user_nom; ?></span>
            </div>
            <div class="detail-item">
                <span><strong>Email :</strong></span>
                <span><?php echo $user_email ?: 'Non spécifié'; ?></span>
            </div>
            <div class="detail-item">
                <span><strong>Rôle :</strong></span>
                <span><?php echo $user_role; ?></span>
            </div>
            <div class="detail-item">
                <span><strong>Membre depuis :</strong></span>
                <span><?php echo date('d/m/Y', strtotime($date_inscription)); ?></span>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-handshake stat-icon icon-blue"></i>
                <span class="stat-number">3</span>
                <span class="stat-label">Actions de paix réalisées</span>
            </div>
            <div class="stat-card">
                <i class="fas fa-calendar-check stat-icon icon-green"></i>
                <span class="stat-number">12</span>
                <span class="stat-label">Jours d'engagement</span>
            </div>
            <div class="stat-card">
                <i class="fas fa-users stat-icon icon-yellow"></i>
                <span class="stat-number">5</span>
                <span class="stat-label">Amis invités</span>
            </div>
            <div class="stat-card">
                <i class="fas fa-trophy stat-icon icon-primary"></i>
                <span class="stat-number">Niveau 2</span>
                <span class="stat-label">Ambassadeur de la paix</span>
            </div>
        </div>

        <div class="actions-grid">
            <div class="action-card">
                <h3><i class="fas fa-user-edit"></i> Gérer mon profil</h3>
                <p>Modifiez vos informations personnelles et votre photo de profil</p>
                <a href="edit-profile.php" class="btn-dashboard btn-edit">
                    <i class="fas fa-edit"></i> Modifier le profil
                </a>
            </div>
            
            <div class="action-card">
                <h3><i class="fas fa-chart-line"></i> Mes statistiques</h3>
                <p>Consultez votre progression et votre impact sur la communauté</p>
                <a href="statistics.php" class="btn-dashboard" style="background: var(--color-primary); color: white;">
                    <i class="fas fa-chart-bar"></i> Voir les stats
                </a>
            </div>
            
            <div class="action-card">
                <h3><i class="fas fa-share-alt"></i> Partager l'engagement</h3>
                <p>Invitez vos amis à rejoindre notre mouvement pour la paix</p>
                <a href="invite.php" class="btn-dashboard" style="background: var(--color-accent); color: white;">
                    <i class="fas fa-user-plus"></i> Inviter des amis
                </a>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="logout.php" class="btn-dashboard btn-logout">
                <i class="fas fa-sign-out-alt"></i> Se déconnecter
            </a>
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
</body>
</html>
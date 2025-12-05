<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Office - Together4Peace</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/stylesBack.css">
</head>

<body>

<?php
// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- HEADER -->
<header class="admin-header">
    <div class="logo">
        <img src="../../assets/images/logo.png" alt="Logo" class="header-logo">
        <span class="site-name">Admin Dashboard</span>
    </div>
    
    <div class="user-controls">
        <span>Bienvenue, Admin !</span>
        <a href="../../View/Frontoffice/index.php" class="btn-donate">
            <i class="fa fa-sign-out-alt"></i> Déconnexion
        </a>
    </div>
</header>

<!-- DASHBOARD WRAPPER -->
<div class="dashboard-wrapper">
    
    <!-- SIDEBAR -->
    <div class="sidebar">
        <nav>
            <ul>
                <li <?= ($current_page == 'index.php') ? 'class="active"' : '' ?>>
                    <a href="index.php"><i class="fa fa-home"></i> Aperçu</a>
                </li>
                <li <?= ($current_page == 'ChartesList.php') ? 'class="active"' : '' ?>>
                    <a href="ChartesList.php"><i class="fa fa-handshake"></i> Chartes Signées</a>
                </li>
                <li <?= ($current_page == 'UserList.php') ? 'class="active"' : '' ?>>
                    <a href="UserList.php"><i class="fa fa-users"></i> Gérer les Utilisateurs</a>
                </li>
                <li <?= ($current_page == 'OffreList.php' || $current_page == 'addOffre.php' || $current_page == 'editOffre.php') ? 'class="active"' : '' ?>>
                    <a href="OffreList.php"><i class="fa fa-bullhorn"></i> Gérer les Offres</a>
                </li>
                <li <?= ($current_page == 'ArticleList.php' || $current_page == 'addArticle.php' || $current_page == 'editArticle.php') ? 'class="active"' : '' ?>>
                    <a href="ArticleList.php"><i class="fa fa-newspaper"></i> Gérer les Articles</a>
                </li>
                <li <?= ($current_page == 'QuizList.php' || $current_page == 'addQuiz.php' || $current_page == 'editQuiz.php') ? 'class="active"' : '' ?>>
                    <a href="QuizList.php"><i class="fa fa-question-circle"></i> Gérer les Quiz</a>
                </li>
                <li <?= ($current_page == 'TestimonialList.php') ? 'class="active"' : '' ?>>
                    <a href="TestimonialList.php"><i class="fa fa-comments"></i> Gérer les Témoignages</a>
                </li>
                <li <?= ($current_page == 'settings.php') ? 'class="active"' : '' ?>>
                    <a href="settings.php"><i class="fa fa-cog"></i> Paramètres</a>
                </li>
                <li <?= ($current_page == 'View/Frontoffice/index.php') ? 'class="active"' : '' ?>>
                    <a href="../../View/Frontoffice/index.php"><i class="fa fa-globe"></i> Site</a>
            </ul>
        </nav>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
<<<<<<< HEAD
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Together4Peace - Tableau de Bord Admin</title>
    <link rel="stylesheet" href="..\..\assets\css\styles.css"> 
    <link rel="stylesheet" href="..\..\assets\css\stylesBack.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <header class="admin-header">
        <a href="index.html" class="logo-link">
            <div class="logo">
                <img src="..\..\assets\images\logo.png" alt="Logo Together4Peace" class="header-logo">
                <span class="site-name">Admin Dashboard</span>
            </div>
        </a>
        <div class="user-controls">
            <span>Bienvenue, Admin !</span>
            <a href="admin-login.html" class="btn btn-donate"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
    </header>

    <div class="dashboard-wrapper">
        
        <aside class="sidebar">
            <nav>
                <ul>
                    <li class="active"><a href="index.php"><i class="fas fa-tachometer-alt"></i> Aperçu</a></li>
                    <li><a href="#"><i class="fas fa-handshake"></i> Chartes Signées</a></li>
                    <li><a href="#"><i class="fas fa-users"></i> Gérer les Utilisateurs</a></li>
                    <li><a href="OffreList.php"><i class="fas fa-bullhorn"></i> Gérer les Offres</a></li>
                    <li><a href="ArticleList.php"><i class="fas fa-newspaper"></i> Gérer les Articles</a></li>
                    <li><a href="addQuiz.php"><i class="fa fa-question-circle"></i> Gérer les Quiz</a></li>
                    <li><a href="#"><i class="fa fa-comments"></i> Gérer les Témoignages</a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> Paramètres</a></li>
                    <li><a href="../../View/Frontoffice/index.php"><i class="fa fa-globe"></i> Site</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <h1>Tableau de Bord</h1>
            
            <section class="stats-cards">
                <div class="stat-card primary">
                    <i class="fas fa-handshake fa-2x"></i>
                    <h3>2,450</h3>
                    <p>Chartes Signées</p>
                </div>
                <div class="stat-card accent">
                    <i class="fas fa-dollar-sign fa-2x"></i>
                    <h3>45,000 €</h3>
                    <p>Fonds Collectés</p>
                </div>
                <div class="stat-card info">
                    <i class="fas fa-user-plus fa-2x"></i>
                    <h3>120</h3>
                    <p>Nouveaux Bénévoles</p>
                </div>
                <div class="stat-card warning">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                    <h3>5</h3>
                    <p>Témoignages en Attente</p>
                </div>
            </section>
            
            <section class="recent-activity">
                <h2>Activité Récente</h2>
                <ul>
                    <li>**[15:30]** Nouvelle signature de Charte par Jean Dupont.</li>
                    <li>**[14:10]** Témoignage "Super Projet" ajouté, en attente de modération.</li>
                    <li>**[10:00]** Mise à jour du statut de l'Offre #12.</li>
                </ul>
            </section>
        </main>
    </div>

</body>
</html>
=======
<?php include "template.php"; ?>


<h2 class="mb-4">Tableau de bord général</h2>

<div class="row g-4">

    <div class="col-md-4">
        <div class="card shadow border-0" style="border-left: 5px solid #f6c23e;">
            <div class="card-body">
                <h6 class="text-muted">Activités cette semaine</h6>
                <h2 class="fw-bold">128</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow border-0" style="border-left: 5px solid #1cc88a;">
            <div class="card-body">
                <h6 class="text-muted">Signalements reçus</h6>
                <h2 class="fw-bold">12</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow border-0" style="border-left: 5px solid #4e73df;">
            <div class="card-body">
                <h6 class="text-muted">Visites ce mois</h6>
                <h2 class="fw-bold">2430</h2>
            </div>
        </div>
    </div>

</div>

<h3 class="mt-5 mb-3">Activités récentes</h3>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover m-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Événement</th>
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2025-11-10</td>
                    <td>Nouvelle publication</td>
                    <td>Article de sensibilisation accepté</td>
                </tr>
                <tr>
                    <td>2025-11-09</td>
                    <td>Signalement</td>
                    <td>Message signalé pour modération</td>
                </tr>
                <tr>
                    <td>2025-11-07</td>
                    <td>Mise à jour site</td>
                    <td>Logo et couleurs actualisés</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
>>>>>>> 70a1b443d163ee0a60357ea7d5e6588e414d81f3

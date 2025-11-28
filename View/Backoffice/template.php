<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Office - Together4Peace</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <style>
        body {
            background-color: #f5f7fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg, #0a7dbc, #1cc6a1);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            font-size: 16px;
        }
        .sidebar a:hover {
            background: rgba(255,255,255,0.15);
        }
        .content {
            margin-left: 260px;
            padding: 25px;
        }
        .topbar {
            height: 60px;
            width: 100%;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding-right: 25px;
            background: white;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-container img {
            width: 80px;
            border-radius: 50%;
        }
        .sidebar-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 25px;
        }
    </style>
</head>

<body>

<div class="sidebar">

    <div class="logo-container">
        <img src="../../assets/images/logo.png" alt="Logo">
    </div>

    <div class="sidebar-title">
        Together4Peace
    </div>

    <a href="index.php"><i class="fa fa-home me-2"></i> Accueil</a>

    <a href="OffreList.php"><i class="fa fa-briefcase me-2"></i> Offres</a>
    <a href="ArticleList.php"><i class="fa fa-briefcase me-2"></i>Articles</a>
    <a href=""><i class="fa fa-users me-2"></i> Paramètres</a>
    <a href="..\..\View\Frontoffice\index.php"><i class="fa fa-sign-out-alt me-2"></i> Site </a>
    
</div>

<div class="topbar">
    <span>Connecté en tant qu'administrateur</span>
</div>

<div class="content">

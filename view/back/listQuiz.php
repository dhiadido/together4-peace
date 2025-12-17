<?php
// On inclut le contrôleur
require_once '../../controller/QuizC.php';

// On récupère la liste
$quizC = new QuizC();
$listeQuiz = $quizC->afficherQuiz();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Quiz - Together4Peace</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Même CSS que addQuiz pour la cohérence */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: #f0f2f5; display: flex; height: 100vh; }
        
        .sidebar { width: 260px; background-color: #2c3e50; color: white; display: flex; flex-direction: column; padding: 20px; position: fixed; height: 100%; }
        
        /* On retire le style par défaut du h2 car on va le gérer en inline avec le logo */
        .sidebar a { text-decoration: none; color: #b0c4de; padding: 15px; margin-bottom: 10px; border-radius: 8px; transition: 0.3s; display: block; }
        .sidebar a:hover, .sidebar a.active { background-color: #34495e; color: white; padding-left: 20px; }

        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; overflow-y: auto; }
        
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #3498db; color: white; }
        tr:hover { background-color: #f9f9f9; }
        
        .btn { padding: 8px 12px; border-radius: 5px; text-decoration: none; color: white; font-size: 0.9em; margin-right: 5px; }
        .btn-edit { background-color: #f39c12; }
        .btn-delete { background-color: #e74c3c; }
        .btn-add { background-color: #2ecc71; float: right; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div style="text-align: center; margin-bottom: 40px;">
            <img src="logo.png" alt="Logo Together4Peace" style="width: 100px; height: auto; margin-bottom: 10px;">
            <h2 style="font-size: 20px; font-weight: 600; letter-spacing: 1px;">Together4Peace</h2>
        </div>

        <a href="../front/addQuiz.php">➕ Ajouter un Quiz</a>
        <a href="listQuiz.php" class="active">📋 Liste des Quiz</a>
        
        <a href="#" style="margin-top: auto; color: #e74c3c;">🚪 Déconnexion</a>
    </div>

    <div class="main-content">
        <a href="../front/addQuiz.php" class="btn btn-add">+ Nouveau Quiz</a>
        <h1>Liste des Quiz existants</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Thème</th>
                    <th>Durée</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Si la liste est vide
                if ($listeQuiz->rowCount() == 0) {
                    echo "<tr><td colspan='5' style='text-align:center; padding:20px;'>Aucun quiz trouvé dans la base de données.</td></tr>";
                }
                
                // Boucle d'affichage
                foreach ($listeQuiz as $quiz) {
                ?>
                <tr>
                    <td><?php echo $quiz['id_quiz']; ?></td>
                    <td><strong><?php echo $quiz['titre']; ?></strong></td>
                    <td><?php echo $quiz['theme']; ?></td>
                    <td><?php echo $quiz['duree_minutes']; ?> min</td>
                    <td>
                        <a href="listQuestion.php?id_quiz=<?php echo $quiz['id_quiz']; ?>" class="btn" style="background-color: #1abc9c;">Gérer Questions</a>
                        
                        <a href="updateQuiz.php?id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-edit">Modifier Quiz</a>
                        
                        <a href="deleteQuiz.php?id=<?php echo $quiz['id_quiz']; ?>" 
                           class="btn btn-delete"
                           onclick="return confirm('Voulez-vous vraiment supprimer ce quiz ?');">
                           Supprimer
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>
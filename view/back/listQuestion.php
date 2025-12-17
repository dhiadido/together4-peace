<?php
require_once '../../controller/QuestionC.php';
require_once '../../controller/QuizC.php';

// Vérification de l'ID du quiz
if (!isset($_GET['id_quiz'])) {
    header('Location: listQuiz.php');
    exit();
}

$id_quiz = $_GET['id_quiz'];
$questionC = new QuestionC();
$quizC = new QuizC();

// Récupérer les informations du quiz parent
$quiz = $quizC->recupererQuiz($id_quiz); 
if (!$quiz) {
    die("Quiz introuvable.");
}

// Récupérer toutes les questions pour ce quiz
$listeQuestions = $questionC->recupererQuestionsParQuiz($id_quiz);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Questions de : <?php echo $quiz['titre']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* CSS de listQuiz.php (pour cohérence) */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: #f0f2f5; display: flex; height: 100vh; }
        .sidebar { width: 260px; background-color: #2c3e50; color: white; display: flex; flex-direction: column; padding: 20px; position: fixed; height: 100%; }
        .sidebar a { text-decoration: none; color: #b0c4de; padding: 15px; margin-bottom: 10px; border-radius: 8px; transition: 0.3s; display: block; }
        .sidebar a:hover { background-color: #34495e; color: white; padding-left: 20px; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; overflow-y: auto; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #3498db; color: white; }
        tr:hover { background-color: #f9f9f9; }
        .btn { padding: 8px 12px; border-radius: 5px; text-decoration: none; color: white; font-size: 0.9em; margin-right: 5px; }
        .btn-edit { background-color: #f39c12; }
        .btn-delete { background-color: #e74c3c; }
        .btn-add { background-color: #2ecc71; float: right; margin-bottom: 20px; }
        .btn-back { background-color: #555; margin-right: 10px;}
    </style>
</head>
<body>
    <div class="sidebar">
        <div style="text-align: center; margin-bottom: 40px;">
            <img src="logo.png" alt="Logo Together4Peace" style="width: 100px; height: auto; margin-bottom: 10px;">
            <h2 style="font-size: 20px; font-weight: 600; letter-spacing: 1px;">Together4Peace</h2>
        </div>
        <a href="addQuiz.php">➕ Ajouter un Quiz</a>
        <a href="listQuiz.php" class="active">📋 Liste des Quiz</a>
        <a href="#" style="margin-top: auto; color: #e74c3c;">🚪 Déconnexion</a>
    </div>

    <div class="main-content">
        <h1>Questions pour : <?php echo $quiz['titre']; ?></h1>
        <p>Thème : **<?php echo $quiz['theme']; ?>**</p>
        
        <a href="listQuiz.php" class="btn btn-back">← Retour à la liste des Quiz</a>
        <a href="addQuestion.php?id_quiz=<?php echo $id_quiz; ?>" class="btn btn-add">+ Nouvelle Question</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Texte de la Question</th>
                    <th>Choix 1</th>
                    <th>Choix 2</th>
                    <th>Choix 3</th>
                    <th>Réponse Correcte</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($listeQuestions) == 0) {
                    echo "<tr><td colspan='7' style='text-align:center; padding:20px;'>Aucune question trouvée pour ce quiz.</td></tr>";
                }
                
                foreach ($listeQuestions as $q) {
                ?>
                <tr>
                    <td><?php echo $q['id_question']; ?></td>
                    <td><?php echo substr($q['texte_question'], 0, 50) . '...'; ?></td>
                    <td><?php echo $q['choix1']; ?></td>
                    <td><?php echo $q['choix2']; ?></td>
                    <td><?php echo $q['choix3']; ?></td>
                    <td>Choix **<?php echo $q['reponse_correcte']; ?>**</td>
                    <td>
                        <a href="updateQuestion.php?id=<?php echo $q['id_question']; ?>&id_quiz=<?php echo $id_quiz; ?>" class="btn btn-edit">Modifier</a>
                        
                        <a href="deleteQuestion.php?id=<?php echo $q['id_question']; ?>&id_quiz=<?php echo $id_quiz; ?>" 
                           class="btn btn-delete"
                           onclick="return confirm('Voulez-vous vraiment supprimer cette question ?');">
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
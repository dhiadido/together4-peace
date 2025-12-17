<?php
require_once '../../controller/QuestionC.php';
require_once '../../model/Question.php';
require_once '../../controller/QuizC.php';

$error = "";
$success = "";

if (!isset($_GET['id_quiz'])) {
    header('Location: listQuiz.php');
    exit();
}
$id_quiz = $_GET['id_quiz'];


if (
    isset($_POST["texte_question"]) && 
    isset($_POST["choix1"]) && 
    isset($_POST["reponse_correcte"])
) {
    if (
        !empty($_POST["texte_question"]) && 
        !empty($_POST["choix1"]) && 
        !empty($_POST["reponse_correcte"])
    ) {
        $question = new Question(
            null, 
            $id_quiz, 
            $_POST['texte_question'],
            $_POST['choix1'],
            $_POST['choix2'],
            $_POST['choix3'],
            $_POST['reponse_correcte']
        );
        
        $questionC = new QuestionC();
        $questionC->ajouterQuestion($question);
        
        
        header("Location: listQuestion.php?id_quiz=$id_quiz");
        exit();
    } else {
        $error = "Veuillez remplir au moins le texte de la question, le choix 1 et la bonne réponse.";
    }
}


$quizC = new QuizC();
$quiz = $quizC->recupererQuiz($id_quiz);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Question - Together4Peace</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* CSS de addQuiz.php (pour cohérence) */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: #f0f2f5; display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background-color: #2c3e50; color: white; display: flex; flex-direction: column; padding: 20px; position: fixed; height: 100%; }
        .sidebar a { text-decoration: none; color: #b0c4de; padding: 15px; margin-bottom: 10px; border-radius: 8px; transition: 0.3s; display: block; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; display: flex; flex-direction: column; align-items: center; }
        .card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 800px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #555; font-weight: 600; }
        input, textarea, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background-color: #fafafa; }
        .btn-submit { background-color: #2ecc71; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold; margin-top: 10px;}
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; border-radius: 8px; margin-bottom: 20px; width: 100%; max-width: 800px; }
        .note { padding: 10px; background-color: #e0f7fa; border-left: 4px solid #00bcd4; margin-top: -10px; margin-bottom: 20px; font-size: 0.9em; }
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
        <h1 style="margin-bottom: 20px;">Ajouter une Question au Quiz : **<?php echo htmlspecialchars($quiz['titre']); ?>**</h1>

        <?php if(!empty($error)) { echo "<div class='alert-danger'>$error</div>"; } ?>

        <div class="card">
            <div class="note">
                Chaque question doit avoir 3 choix et une seule réponse correcte.
            </div>
            <!-- novalidate pour désactiver les contrôles HTML natifs -->
            <form action="addQuestion.php?id_quiz=<?php echo $id_quiz; ?>" method="POST" novalidate>
                
                <div class="form-group">
                    <label for="texte_question">Texte de la Question</label>
                    <textarea id="texte_question" name="texte_question" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="choix1">Choix 1</label>
                    <input type="text" id="choix1" name="choix1">
                </div>
                
                <div class="form-group">
                    <label for="choix2">Choix 2</label>
                    <input type="text" id="choix2" name="choix2">
                </div>

                <div class="form-group">
                    <label for="choix3">Choix 3</label>
                    <input type="text" id="choix3" name="choix3">
                </div>

                <div class="form-group">
                    <label for="reponse_correcte">Réponse Correcte (Entrez le numéro : 1, 2 ou 3)</label>
                    <select id="reponse_correcte" name="reponse_correcte">
                        <option value="">-- Sélectionner --</option>
                        <option value="1">Choix 1</option>
                        <option value="2">Choix 2</option>
                        <option value="3">Choix 3</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Enregistrer la Question</button>
            </form>
        </div>
    </div>
</body>
</html>

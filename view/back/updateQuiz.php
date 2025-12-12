<?php
require_once '../../controller/QuizC.php';
require_once '../../model/Quiz.php';

$error = "";
$success = "";
$quizC = new QuizC();


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $ancienQuiz = $quizC->recupererQuiz($id);
} else {
    header('Location: listQuiz.php'); 
    exit();
}


if (isset($_POST["titre"]) && isset($_POST["duree"])) {
    if (!empty($_POST["titre"]) && !empty($_POST["duree"])) {
        
        $quiz = new Quiz(
            $id, // On garde le même ID
            $_POST['titre'],
            $_POST['description'],
            $_POST['duree'],
            $_POST['theme']
        );
        
        $quizC->modifierQuiz($quiz, $id);
        
        
        header('Location: listQuiz.php');
        exit();
    } else {
        $error = "Champs manquants !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Quiz - Together4Peace</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: #f0f2f5; display: flex; height: 100vh; }
        
        .sidebar { width: 260px; background-color: #2c3e50; color: white; display: flex; flex-direction: column; padding: 20px; position: fixed; height: 100%; }
        
       
        .sidebar a { text-decoration: none; color: #b0c4de; padding: 15px; margin-bottom: 10px; border-radius: 8px; display: block; transition: 0.3s;}
        .sidebar a:hover { background-color: #34495e; color: white; padding-left: 20px; }
        
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; display: flex; flex-direction: column; align-items: center; }
        .card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 800px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; }
        .btn-submit { background-color: #f39c12; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; width: 100%; font-weight: bold; font-size: 16px;}
        .btn-submit:hover { background-color: #e67e22; }
    </style>
</head>
<body>
    
    <div class="sidebar">
        <div style="text-align: center; margin-bottom: 40px;">
            <img src="logo.png" alt="Logo Together4Peace" style="width: 100px; height: auto; margin-bottom: 10px;">
            <h2 style="font-size: 20px; font-weight: 600; letter-spacing: 1px;">Together4Peace</h2>
        </div>

        <a href="addQuiz.php">➕ Ajouter un Quiz</a>
        <a href="listQuiz.php">📋 Liste des Quiz</a>
        <a href="#" style="margin-top: auto; color: #e74c3c;">🚪 Déconnexion</a>
    </div>

    <div class="main-content">
        <h1>Modifier le Quiz #<?php echo $id; ?></h1>

        <div class="card">
            <form action="" method="POST">
                <div class="form-group">
                    <label>Titre</label>
                    <input type="text" name="titre" value="<?php echo $ancienQuiz['titre']; ?>">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4"><?php echo $ancienQuiz['description']; ?></textarea>
                </div>

                <div class="form-group">
                    <label>Durée (min)</label>
                    <input type="number" name="duree" value="<?php echo $ancienQuiz['duree_minutes']; ?>">
                </div>

                <div class="form-group">
                    <label>Thème</label>
                    <select name="theme">
                        <option value="Paix" <?php if($ancienQuiz['theme'] == 'Paix') echo 'selected'; ?>>Paix</option>
                        <option value="Tolérance" <?php if($ancienQuiz['theme'] == 'Tolérance') echo 'selected'; ?>>Tolérance</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Mettre à jour</button>
            </form>
        </div>
    </div>
</body>
</html>
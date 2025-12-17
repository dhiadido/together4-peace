<?php

require_once '../../controller/QuizC.php';
require_once '../../model/Quiz.php';

$error = "";
$success = "";


if (
    isset($_POST["titre"]) && 
    isset($_POST["duree"]) && 
    isset($_POST["theme"])
) {
    if (
        !empty($_POST["titre"]) && 
        !empty($_POST["duree"]) && 
        !empty($_POST["theme"])
    ) {
        $quiz = new Quiz(
            null,
            $_POST['titre'],
            $_POST['description'],
            $_POST['duree'],
            $_POST['theme']
        );
        
        $quizC = new QuizC();
        $quizC->ajouterQuiz($quiz);
        
     
        $success = "Quiz ajouté avec succès ! Vous pouvez le voir dans la liste.";
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Quiz - Together4Peace</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /*css*/
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: #f0f2f5; display: flex; height: 100vh; }
        
        .sidebar { width: 260px; background-color: #2c3e50; color: white; display: flex; flex-direction: column; padding: 20px; position: fixed; height: 100%; }
        
        
        .sidebar a { text-decoration: none; color: #b0c4de; padding: 15px; margin-bottom: 10px; border-radius: 8px; transition: 0.3s; display: block; }
        .sidebar a:hover, .sidebar a.active { background-color: #34495e; color: white; padding-left: 20px; }
        
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; display: flex; flex-direction: column; align-items: center; }
        .card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 800px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #555; font-weight: 600; }
        input, textarea, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background-color: #fafafa; }
        .btn-submit { background-color: #3498db; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold; margin-top: 10px;}
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; width: 100%; max-width: 800px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .error-msg { color: red; font-size: 0.85em; margin-top: 5px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div style="text-align: center; margin-bottom: 40px;">
            <img src="logo.png" alt="Logo Together4Peace" style="width: 100px; height: auto; margin-bottom: 10px;">
            <h2 style="font-size: 20px; font-weight: 600; letter-spacing: 1px;">Together4Peace</h2>
        </div>

        <a href="addQuiz.php" class="active">➕ Ajouter un Quiz</a>
        <a href="../back/listQuiz.php">📋 Liste des Quiz</a>
        <a href="#" style="margin-top: auto; color: #e74c3c;">🚪 Déconnexion</a>
    </div>

    <div class="main-content">
        <h1 style="margin-bottom: 20px;">Créer un nouveau Quiz</h1>

        <?php if(!empty($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <?php if(!empty($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>

        <div class="card">
            <form action="" method="POST" id="formQuiz" novalidate>
                
                <div class="form-group">
                    <label for="titre">Titre du Quiz</label>
                    <input type="text" id="titre" name="titre" placeholder="Ex : La Tolérance">
                    <span id="errorTitre" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="duree">Durée (minutes)</label>
                    <input type="number" id="duree" name="duree">
                    <span id="errorDuree" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label for="theme">Thème associé</label>
                    <select id="theme" name="theme">
                        <option value="">-- Sélectionner --</option>
                        <option value="Paix">🕊️ Paix</option>
                        <option value="Tolérance">🤝 Tolérance</option>
                    </select>
                    <span id="errorTheme" class="error-msg"></span>
                </div>

                <button type="submit" class="btn-submit" onclick="validerFormulaire(event)">Enregistrer le Quiz</button>
            </form>
        </div>
    </div>

    <script>
        function validerFormulaire(e) {
            var titre = document.getElementById("titre").value;
            var duree = document.getElementById("duree").value;
            var theme = document.getElementById("theme").value;
            var isValid = true;
            
            document.getElementById("errorTitre").innerHTML = "";
            document.getElementById("errorDuree").innerHTML = "";
            document.getElementById("errorTheme").innerHTML = "";

            if (titre.length < 3) { document.getElementById("errorTitre").innerHTML = "Titre trop court."; isValid = false; }
            if (duree <= 0) { document.getElementById("errorDuree").innerHTML = "Durée invalide."; isValid = false; }
            if (theme === "") { document.getElementById("errorTheme").innerHTML = "Thème requis."; isValid = false; }

            if (!isValid) e.preventDefault();
        }
    </script>
</body>
</html>
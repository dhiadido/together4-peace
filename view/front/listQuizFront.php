<?php

require_once __DIR__ . '/../../controller/QuizC.php'; 

$quizC = new QuizC();

$listeQuiz = $quizC->afficherQuiz();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Coin Éducatif - Together4Peace</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #ecf0f1; padding: 20px; }
        .header { text-align: center; margin-bottom: 40px; }
        .header h1 { color: #2c3e50; }
        .quiz-list { max-width: 900px; margin: auto; }
        .quiz-card { background: white; padding: 20px; margin-bottom: 15px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .quiz-info h2 { color: #3498db; margin-top: 0; }
        .quiz-info p { color: #7f8c8d; }
        .quiz-info strong { color: #2c3e50; }
        .quiz-start a { text-decoration: none; background-color: #2ecc71; color: white; padding: 10px 20px; border-radius: 5px; font-weight: bold; transition: background-color 0.3s; }
        .quiz-start a:hover { background-color: #27ae60; }
        .empty-message { text-align: center; color: #e74c3c; margin-top: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🕊️ Coin Éducatif : Quiz sur la Paix et l'Inclusion 🤝</h1>
        <p>Testez vos connaissances et sensibilisez-vous sur les valeurs de la tolérance et de la paix.</p>
    </div>

    <div class="quiz-list">
        <?php 
        if ($listeQuiz instanceof PDOStatement && $listeQuiz->rowCount() > 0) {
            foreach ($listeQuiz as $quiz) { 
        ?>
            <div class="quiz-card">
                <div class="quiz-info">
                    <h2><?php echo htmlspecialchars($quiz['titre']); ?></h2>
                    <p><?php echo htmlspecialchars($quiz['description']); ?></p>
                    <p>Thème : <strong><?php echo htmlspecialchars($quiz['theme']); ?></strong> | Durée estimée : <strong><?php echo htmlspecialchars($quiz['duree_minutes']); ?> min</strong></p>
                </div>
                <div class="quiz-start">
                    <a href="passerQuiz.php?id=<?php echo $quiz['id_quiz']; ?>">Commencer →</a>
                </div>
            </div>
        <?php 
            }
        } else {
            echo '<p class="empty-message">Aucun quiz disponible pour le moment. Revenez bientôt !</p>';
        }
        ?>
    </div>
</body>
</html>
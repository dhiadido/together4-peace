<?php

require_once '../../controller/QuizC.php';


if (isset($_GET["id"])) {
    $quizC = new QuizC();
    $quizC->supprimerQuiz($_GET["id"]);
    
    
    header('Location: listQuiz.php');
    exit();
} else {
    echo "Aucun ID spécifié pour la suppression.";
}
?>
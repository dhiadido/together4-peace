<?php

require_once '../../controller/QuestionC.php';

if (isset($_GET["id"]) && isset($_GET["id_quiz"])) {
    
    $id_question = $_GET["id"];
    $id_quiz = $_GET["id_quiz"];

    
    $questionC = new QuestionC();
    $questionC->supprimerQuestion($id_question);
    
    header("Location: listQuestion.php?id_quiz=$id_quiz");
    exit();
} else {
    
    echo "Erreur : ID de la question ou ID du Quiz parent non spécifié pour la suppression.";
}
?>
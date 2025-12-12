<?php

require_once __DIR__ . '/../config/Connect.php';
require_once __DIR__ . '/../model/Question.php'; 

class QuestionC {

    
    public function ajouterQuestion(Question $q) {
        $sql = "INSERT INTO question (id_quiz, texte_question, choix1, choix2, choix3, reponse_correcte) 
                VALUES (:id_quiz, :texte, :c1, :c2, :c3, :rep)";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_quiz' => $q->getIdQuiz(),
                'texte' => $q->getTexteQuestion(),
                'c1' => $q->getChoix1(),
                'c2' => $q->getChoix2(),
                'c3' => $q->getChoix3(),
                'rep' => $q->getReponseCorrecte()
            ]);
        } catch (Exception $e) { die('Erreur Ajout Question: ' . $e->getMessage()); }
    }

    
    public function recupererQuestionsParQuiz($id_quiz) {
        $sql = "SELECT * FROM question WHERE id_quiz = :id_quiz ORDER BY id_question ASC";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_quiz', $id_quiz);
            $query->execute();
            return $query->fetchAll(); 
        } catch (Exception $e) { die('Erreur Affichage Questions: ' . $e->getMessage()); }
    }
    
    
    public function recupererQuestion($id) {
        $sql = "SELECT * FROM question WHERE id_question = :id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
            return $query->fetch(); 
        } catch (Exception $e) { die('Erreur Récupération Question: ' . $e->getMessage()); }
    }

   
    public function modifierQuestion(Question $q, $id) {
        $sql = "UPDATE question SET texte_question=:texte, choix1=:c1, choix2=:c2, choix3=:c3, reponse_correcte=:rep WHERE id_question=:id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'texte' => $q->getTexteQuestion(),
                'c1' => $q->getChoix1(),
                'c2' => $q->getChoix2(),
                'c3' => $q->getChoix3(),
                'rep' => $q->getReponseCorrecte(),
                'id' => $id
            ]);
        } catch (Exception $e) { die('Erreur Modification Question: ' . $e->getMessage()); }
    }

    // 5. SUPPRIMER une Question
    public function supprimerQuestion($id) {
        $sql = "DELETE FROM question WHERE id_question = :id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
        } catch (Exception $e) { die('Erreur Suppression Question: ' . $e->getMessage()); }
    }
}
?>
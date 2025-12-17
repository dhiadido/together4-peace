<?php

require_once __DIR__ . '/../config/Connect.php'; 
require_once __DIR__ . '/../model/Quiz.php';

class QuizC {


    public function ajouterQuiz($quiz) {
        $sql = "INSERT INTO quiz (titre, description, duree_minutes, theme) VALUES (:titre, :desc, :duree, :theme)";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'titre' => $quiz->getTitre(),
                'desc' => $quiz->getDescription(),
                'duree' => $quiz->getDuree(),
                'theme' => $quiz->getTheme()
            ]);
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    
    public function afficherQuiz() {
        $sql = "SELECT * FROM quiz ORDER BY id_quiz DESC";
        $db = Config::getConnexion();
        try { return $db->query($sql); } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    
    public function supprimerQuiz($id) {
        $sql = "DELETE FROM quiz WHERE id_quiz = :id";
        $db = Config::getConnexion(); 
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

 
    public function recupererQuiz($id) {
        $sql = "SELECT * FROM quiz WHERE id_quiz = :id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
            return $query->fetch(); 
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    
    public function modifierQuiz($quiz, $id) {
        $sql = "UPDATE quiz SET titre=:titre, description=:desc, duree_minutes=:duree, theme=:theme WHERE id_quiz=:id";
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'titre' => $quiz->getTitre(),
                'desc' => $quiz->getDescription(),
                'duree' => $quiz->getDuree(),
                'theme' => $quiz->getTheme(),
                'id' => $id
            ]);
        } catch (Exception $e) { die('Erreur: ' . $e->getMessage()); }
    }

    public function enregistrerResultatEtCertif($nom_utilisateur, $id_quiz, $pourcentage) {
        
        $certif_obtenu = ($pourcentage >= 80) ? 1 : 0;

        $sql = "INSERT INTO resultats_quiz_certif (nom_utilisateur, id_quiz, score_pourcentage, certificat_obtenu) 
                VALUES (:nom_user, :id_quiz, :pourcentage, :certif)";
        $db = Config::getConnexion();
        
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'nom_user' => $nom_utilisateur, 
                'id_quiz' => $id_quiz,
                'pourcentage' => $pourcentage,
                'certif' => $certif_obtenu
            ]);
            return $certif_obtenu; 
        } catch (Exception $e) { 
             error_log('Erreur d\'enregistrement du résultat: ' . $e->getMessage());
             return false;
        }
    }
}
?>
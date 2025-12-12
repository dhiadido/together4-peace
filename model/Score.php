<?php
require_once __DIR__ . '/../config/Connect.php';

class Score {
    private $pdo;

    public function __construct() {
        $this->pdo = Config::getConnexion();
    }

    public function addScore($id_quiz, $user_name, $user_email, $score, $totalQuestions, $pourcentage) {
        $sql = "INSERT INTO scores (id_quiz, user_name, user_email, score, total_questions, pourcentage)
                VALUES (:id_quiz, :user_name, :user_email, :score, :totalQuestions, :pourcentage)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_quiz' => $id_quiz,
            ':user_name' => $user_name,
            ':user_email' => $user_email,
            ':score' => $score,
            ':totalQuestions' => $totalQuestions,
            ':pourcentage' => $pourcentage
        ]);
    }

    public function topGlobal() {
        return $this->pdo->query("SELECT * FROM scores ORDER BY pourcentage DESC LIMIT 20")->fetchAll();
    }

    public function topByQuiz($id_quiz) {
        $stmt = $this->pdo->prepare("SELECT * FROM scores WHERE id_quiz = ? ORDER BY pourcentage DESC LIMIT 20");
        $stmt->execute([$id_quiz]);
        return $stmt->fetchAll();
    }

    public function getAllScores() {
        return $this->pdo->query("SELECT * FROM scores ORDER BY submitted_at DESC")->fetchAll();
    }
}

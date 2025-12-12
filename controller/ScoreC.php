<?php
require_once __DIR__ . '/../model/Score.php';

class ScoreC {
    private $model;

    public function __construct() {
        $this->model = new Score();
    }

    public function ajouterScore($id_quiz, $nom, $email, $score, $totalQuestions, $pourcentage) {
        return $this->model->addScore($id_quiz, $nom, $email, $score, $totalQuestions, $pourcentage);
    }

    public function top10Global() {
        return $this->model->topGlobal();
    }

    public function top10Quiz($id_quiz) {
        return $this->model->topByQuiz($id_quiz);
    }

    public function exporter() {
        return $this->model->getAllScores();
    }
}

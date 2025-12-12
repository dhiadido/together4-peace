<?php

class Question {
    private $id_question;
    private $id_quiz; 
    private $texte_question;
    private $choix1;
    private $choix2;
    private $choix3;
    private $reponse_correcte; 

    /**
     * Constructeur
     * * @param int|null $id_q ID de la question (null pour un nouvel enregistrement)
     * @param int $id_quiz ID du quiz parent
     * @param string $texte Texte de la question
     * @param string $c1 Choix 1
     * @param string $c2 Choix 2
     * @param string $c3 Choix 3
     * @param int $rep Numéro de la réponse correcte (1, 2 ou 3)
     */
    public function __construct($id_q, $id_quiz, $texte, $c1, $c2, $c3, $rep) {
        $this->id_question = $id_q;
        $this->id_quiz = $id_quiz;
        $this->texte_question = $texte;
        $this->choix1 = $c1;
        $this->choix2 = $c2;
        $this->choix3 = $c3;
        $this->reponse_correcte = $rep;
    }


    public function getIdQuestion() { return $this->id_question; }
    public function getIdQuiz() { return $this->id_quiz; }
    public function getTexteQuestion() { return $this->texte_question; }
    public function getChoix1() { return $this->choix1; }
    public function getChoix2() { return $this->choix2; }
    public function getChoix3() { return $this->choix3; }
    public function getReponseCorrecte() { return $this->reponse_correcte; }

    

    public function setIdQuiz($id_quiz) { $this->id_quiz = $id_quiz; }
    public function setTexteQuestion($texte) { $this->texte_question = $texte; }
    public function setChoix1($c1) { $this->choix1 = $c1; }
    public function setChoix2($c2) { $this->choix2 = $c2; }
    public function setChoix3($c3) { $this->choix3 = $c3; }
    public function setReponseCorrecte($rep) { $this->reponse_correcte = $rep; }
}
?>
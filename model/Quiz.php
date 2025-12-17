<?php
class Quiz {
    private $id_quiz;
    private $titre;
    private $description;
    private $duree;
    private $theme;

    
    public function __construct($id = null, $titre, $description, $duree, $theme) {
        $this->id_quiz = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->duree = $duree;
        $this->theme = $theme;
    }

   
    public function getId() { return $this->id_quiz; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getDuree() { return $this->duree; }
    public function getTheme() { return $this->theme; }

    
    public function setTitre($titre) { $this->titre = $titre; }
    public function setDescription($description) { $this->description = $description; }
    public function setDuree($duree) { $this->duree = $duree; }
    public function setTheme($theme) { $this->theme = $theme; }
}
?>
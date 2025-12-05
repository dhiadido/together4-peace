<<<<<<< HEAD
<?php

class Article {
    private $id;
    private $titre;
    private $theme;
    private $resume;
    private $contenu;
    private $image;

    // Constructor
    public function __construct($titre = null, $theme = null, $resume = null, 
                                $contenu = null, 
                                $image = '../../assets/images/logo.png') 
    {
        $this->titre = $titre;
        $this->theme = $theme;
        $this->resume = $resume;
        $this->contenu = $contenu;
        $this->image = $image;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getTheme() {
        return $this->theme;
    }

    public function getResume() {
        return $this->resume;
    }

    public function getContenu() {
        return $this->contenu;
    }

    public function getImage() {
        return $this->image;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function setTheme($theme) {
        $this->theme = $theme;
    }

    public function setResume($resume) {
        $this->resume = $resume;
    }

    public function setContenu($contenu) {
        $this->contenu = $contenu;
    }

    public function setImage($image) {
        $this->image = $image;
    }
}

?>
=======
<?php

class Article {
    private $id;
    private $titre;
    private $theme;
    private $resume;
    private $contenu;
    private $image;

    // Constructor
    public function __construct($titre = null, $theme = null, $resume = null, 
                                $contenu = null, 
                                $image = '../../assets/images/logo.png') 
    {
        $this->titre = $titre;
        $this->theme = $theme;
        $this->resume = $resume;
        $this->contenu = $contenu;
        $this->image = $image;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getTheme() {
        return $this->theme;
    }

    public function getResume() {
        return $this->resume;
    }

    public function getContenu() {
        return $this->contenu;
    }

    public function getImage() {
        return $this->image;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function setTheme($theme) {
        $this->theme = $theme;
    }

    public function setResume($resume) {
        $this->resume = $resume;
    }

    public function setContenu($contenu) {
        $this->contenu = $contenu;
    }

    public function setImage($image) {
        $this->image = $image;
    }
}

?>
>>>>>>> 70a1b443d163ee0a60357ea7d5e6588e414d81f3

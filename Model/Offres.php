<?php

class Offre {
    private $id;
    private $nomSpecialiste;
    private $description;
    private $prix;
    private $categorie;
    private $categorieProbleme;
    private $contact;
    private $image;
    private $article_id;

    // Constructor - PAS D'IMAGE PAR DÉFAUT
    public function __construct($nomSpecialiste = null, $description = null, $prix = null, 
                                $categorie = null, $categorieProbleme = null, $contact = null, 
                                $image = null, $article_id = null) {
        $this->nomSpecialiste = $nomSpecialiste;
        $this->description = $description;
        $this->prix = $prix;
        $this->categorie = $categorie;
        $this->categorieProbleme = $categorieProbleme;
        $this->contact = $contact;
        $this->image = $image;
        $this->article_id = $article_id;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNomSpecialiste() {
        return $this->nomSpecialiste;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrix() {
        return $this->prix;
    }

    public function getCategorie() {
        return $this->categorie;
    }

    public function getCategorieProbleme() {
        return $this->categorieProbleme;
    }

    public function getContact() {
        return $this->contact;
    }

    public function getImage() {
        return $this->image;
    }

    public function getArticleId() {
        return $this->article_id;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNomSpecialiste($nomSpecialiste) {
        $this->nomSpecialiste = $nomSpecialiste;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setPrix($prix) {
        $this->prix = $prix;
    }

    public function setCategorie($categorie) {
        $this->categorie = $categorie;
    }

    public function setCategorieProbleme($categorieProbleme) {
        $this->categorieProbleme = $categorieProbleme;
    }

    public function setContact($contact) {
        $this->contact = $contact;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function setArticleId($article_id) {
        $this->article_id = $article_id;
    }
}

?>
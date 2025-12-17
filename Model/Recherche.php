<?php

class Recherche {
    private $id;
    private $nomChercheur;
    private $domaine;
    private $specialite;
    private $institution;
    private $description;
    private $publications;
    private $email;
    private $telephone;
    private $image;
    private $cv;
    private $disponibilite;

    public function __construct($nomChercheur = null, $domaine = null, $specialite = null,
                                $institution = null, $description = null, $publications = null,
                                $email = null, $telephone = null, $image = null, 
                                $cv = null, $disponibilite = null) {
        $this->nomChercheur = $nomChercheur;
        $this->domaine = $domaine;
        $this->specialite = $specialite;
        $this->institution = $institution;
        $this->description = $description;
        $this->publications = $publications;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->image = $image;
        $this->cv = $cv;
        $this->disponibilite = $disponibilite;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNomChercheur() { return $this->nomChercheur; }
    public function getDomaine() { return $this->domaine; }
    public function getSpecialite() { return $this->specialite; }
    public function getInstitution() { return $this->institution; }
    public function getDescription() { return $this->description; }
    public function getPublications() { return $this->publications; }
    public function getEmail() { return $this->email; }
    public function getTelephone() { return $this->telephone; }
    public function getImage() { return $this->image; }
    public function getCv() { return $this->cv; }
    public function getDisponibilite() { return $this->disponibilite; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNomChercheur($nomChercheur) { $this->nomChercheur = $nomChercheur; }
    public function setDomaine($domaine) { $this->domaine = $domaine; }
    public function setSpecialite($specialite) { $this->specialite = $specialite; }
    public function setInstitution($institution) { $this->institution = $institution; }
    public function setDescription($description) { $this->description = $description; }
    public function setPublications($publications) { $this->publications = $publications; }
    public function setEmail($email) { $this->email = $email; }
    public function setTelephone($telephone) { $this->telephone = $telephone; }
    public function setImage($image) { $this->image = $image; }
    public function setCv($cv) { $this->cv = $cv; }
    public function setDisponibilite($disponibilite) { $this->disponibilite = $disponibilite; }
}

?>
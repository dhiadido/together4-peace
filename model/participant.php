<?php

class Participant
{
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $pays;
    private $langue_preferee;
    private $temoignage;
    private $date_inscription;

    public function __construct(
        $nom,
        $prenom,
        $email,
        $pays = null,
        $langue_preferee = null,
        $temoignage = null,
        $date_inscription = null
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->pays = $pays;
        $this->langue_preferee = $langue_preferee;
        $this->temoignage = $temoignage;
        $this->date_inscription = $date_inscription;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPays()
    {
        return $this->pays;
    }

    public function setPays($pays)
    {
        $this->pays = $pays;
    }

    public function getLanguePreferee()
    {
        return $this->langue_preferee;
    }

    public function setLanguePreferee($langue_preferee)
    {
        $this->langue_preferee = $langue_preferee;
    }

    public function getTemoignage()
    {
        return $this->temoignage;
    }

    public function setTemoignage($temoignage)
    {
        $this->temoignage = $temoignage;
    }

    public function getDateInscription()
    {
        return $this->date_inscription;
    }

    public function setDateInscription($date_inscription)
    {
        $this->date_inscription = $date_inscription;
    }
}

?>


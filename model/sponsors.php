<?php

class Sponsors
{
    private $id;
    private $nom_entreprise;
    private $contact_email;
    private $pays;
    private $montant;
    private $date_sponsorisation;
    private $participant_id;

    public function __construct(
        $nom_entreprise,
        $contact_email,
        $participant_id,
        $pays = null,
        $montant = null,
        $date_sponsorisation = null
    ) {
        $this->nom_entreprise = $nom_entreprise;
        $this->contact_email = $contact_email;
        $this->participant_id = $participant_id;
        $this->pays = $pays;
        $this->montant = $montant;
        $this->date_sponsorisation = $date_sponsorisation;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNomEntreprise()
    {
        return $this->nom_entreprise;
    }

    public function setNomEntreprise($nom_entreprise)
    {
        $this->nom_entreprise = $nom_entreprise;
    }

    public function getContactEmail()
    {
        return $this->contact_email;
    }

    public function setContactEmail($contact_email)
    {
        $this->contact_email = $contact_email;
    }

    public function getPays()
    {
        return $this->pays;
    }

    public function setPays($pays)
    {
        $this->pays = $pays;
    }

    public function getMontant()
    {
        return $this->montant;
    }

    public function setMontant($montant)
    {
        $this->montant = $montant;
    }

    public function getDateSponsorisation()
    {
        return $this->date_sponsorisation;
    }

    public function setDateSponsorisation($date_sponsorisation)
    {
        $this->date_sponsorisation = $date_sponsorisation;
    }

    public function getParticipantId()
    {
        return $this->participant_id;
    }

    public function setParticipantId($participant_id)
    {
        $this->participant_id = $participant_id;
    }
}

?>


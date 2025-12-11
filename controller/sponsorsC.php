<?php

require_once dirname(__DIR__) . '/controller/config.php';
require_once dirname(__DIR__) . '/model/sponsors.php';

class SponsorsController
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = config::getConnexion();
    }

    public function createSponsor(Sponsors $sponsor)
    {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO sponsors (nom_entreprise, contact_email, pays, montant, date_sponsorisation, participant_id)
                 VALUES (:nom_entreprise, :contact_email, :pays, :montant, COALESCE(:date_sponsorisation, CURRENT_TIMESTAMP), :participant_id)"
            );

            $stmt->execute([
                ':nom_entreprise' => $sponsor->getNomEntreprise(),
                ':contact_email' => $sponsor->getContactEmail(),
                ':pays' => $sponsor->getPays(),
                ':montant' => $sponsor->getMontant(),
                ':date_sponsorisation' => $sponsor->getDateSponsorisation(),
                ':participant_id' => $sponsor->getParticipantId()
            ]);

            $sponsor->setId($this->pdo->lastInsertId());
            return true;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la création du sponsor : " . $e->getMessage());
        }
    }

    public function getSponsorById($id)
    {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT s.*, 
                        p.id as participant_id_full,
                        p.nom as participant_nom,
                        p.prenom as participant_prenom,
                        p.email as participant_email,
                        p.pays as participant_pays
                 FROM sponsors s
                 INNER JOIN participant p ON s.participant_id = p.id
                 WHERE s.id = :id"
            );
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return null;
            }

            $sponsor = new Sponsors(
                $row['nom_entreprise'],
                $row['contact_email'],
                $row['participant_id'],
                $row['pays'],
                $row['montant'],
                $row['date_sponsorisation']
            );

            $sponsor->setId($row['id']);
            
            // Ajouter les informations du participant au résultat
            $sponsor->participant_info = [
                'id' => $row['participant_id_full'],
                'nom' => $row['participant_nom'],
                'prenom' => $row['participant_prenom'],
                'email' => $row['participant_email'],
                'pays' => $row['participant_pays']
            ];

            return $sponsor;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération du sponsor : " . $e->getMessage());
        }
    }

    public function getAllSponsors()
    {
        try {
            $stmt = $this->pdo->query(
                "SELECT s.*, 
                        p.id as participant_id_full,
                        p.nom as participant_nom,
                        p.prenom as participant_prenom,
                        p.email as participant_email,
                        p.pays as participant_pays
                 FROM sponsors s
                 INNER JOIN participant p ON s.participant_id = p.id
                 ORDER BY s.date_sponsorisation DESC, s.id DESC"
            );
            
            $sponsors = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $sponsor = new Sponsors(
                    $row['nom_entreprise'],
                    $row['contact_email'],
                    $row['participant_id'],
                    $row['pays'],
                    $row['montant'],
                    $row['date_sponsorisation']
                );
                $sponsor->setId($row['id']);
                
                // Ajouter les informations du participant
                $sponsor->participant_info = [
                    'id' => $row['participant_id_full'],
                    'nom' => $row['participant_nom'],
                    'prenom' => $row['participant_prenom'],
                    'email' => $row['participant_email'],
                    'pays' => $row['participant_pays']
                ];
                
                $sponsors[] = $sponsor;
            }
            
            return $sponsors;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des sponsors : " . $e->getMessage());
        }
    }

    public function updateSponsor(Sponsors $sponsor)
    {
        if (!$sponsor->getId()) {
            throw new InvalidArgumentException("L'identifiant du sponsor est requis pour la mise à jour.");
        }

        try {
            $stmt = $this->pdo->prepare(
                "UPDATE sponsors
                 SET nom_entreprise = :nom_entreprise,
                     contact_email = :contact_email,
                     pays = :pays,
                     montant = :montant,
                     date_sponsorisation = :date_sponsorisation,
                     participant_id = :participant_id
                 WHERE id = :id"
            );

            $stmt->execute([
                ':nom_entreprise' => $sponsor->getNomEntreprise(),
                ':contact_email' => $sponsor->getContactEmail(),
                ':pays' => $sponsor->getPays(),
                ':montant' => $sponsor->getMontant(),
                ':date_sponsorisation' => $sponsor->getDateSponsorisation(),
                ':participant_id' => $sponsor->getParticipantId(),
                ':id' => $sponsor->getId()
            ]);

            return true;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la mise à jour du sponsor : " . $e->getMessage());
        }
    }

    public function deleteSponsor($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM sponsors WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la suppression du sponsor : " . $e->getMessage());
        }
    }

    public function getSponsorsByParticipantId($participant_id)
    {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT s.*, 
                        p.id as participant_id_full,
                        p.nom as participant_nom,
                        p.prenom as participant_prenom,
                        p.email as participant_email,
                        p.pays as participant_pays
                 FROM sponsors s
                 INNER JOIN participant p ON s.participant_id = p.id
                 WHERE s.participant_id = :participant_id
                 ORDER BY s.date_sponsorisation DESC, s.id DESC"
            );
            $stmt->execute([':participant_id' => $participant_id]);
            
            $sponsors = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $sponsor = new Sponsors(
                    $row['nom_entreprise'],
                    $row['contact_email'],
                    $row['participant_id'],
                    $row['pays'],
                    $row['montant'],
                    $row['date_sponsorisation']
                );
                $sponsor->setId($row['id']);
                
                // Ajouter les informations du participant
                $sponsor->participant_info = [
                    'id' => $row['participant_id_full'],
                    'nom' => $row['participant_nom'],
                    'prenom' => $row['participant_prenom'],
                    'email' => $row['participant_email'],
                    'pays' => $row['participant_pays']
                ];
                
                $sponsors[] = $sponsor;
            }
            
            return $sponsors;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des sponsors par participant : " . $e->getMessage());
        }
    }
}

?>


<?php

require_once dirname(__DIR__) . '/controller/config.php';
require_once dirname(__DIR__) . '/model/participant.php';

class ParticipantController
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = config::getConnexion();
    }

    public function createParticipant(Participant $participant)
    {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO participant (nom, prenom, email, pays, langue_preferee, temoignage, date_inscription)
                 VALUES (:nom, :prenom, :email, :pays, :langue_preferee, :temoignage, COALESCE(:date_inscription, CURRENT_TIMESTAMP))"
            );

            $stmt->execute([
                ':nom' => $participant->getNom(),
                ':prenom' => $participant->getPrenom(),
                ':email' => $participant->getEmail(),
                ':pays' => $participant->getPays(),
                ':langue_preferee' => $participant->getLanguePreferee(),
                ':temoignage' => $participant->getTemoignage(),
                ':date_inscription' => $participant->getDateInscription()
            ]);

            $participant->setId($this->pdo->lastInsertId());
            return true;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la création du participant : " . $e->getMessage());
        }
    }

    public function getParticipantById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM participant WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return null;
            }

            $participant = new Participant(
                $row['nom'],
                $row['prenom'],
                $row['email'],
                $row['pays'],
                $row['langue_preferee'],
                $row['temoignage'],
                $row['date_inscription']
            );

            $participant->setId($row['id']);
            return $participant;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération du participant : " . $e->getMessage());
        }
    }

    public function updateParticipant(Participant $participant)
    {
        if (!$participant->getId()) {
            throw new InvalidArgumentException("L'identifiant du participant est requis pour la mise à jour.");
        }

        try {
            $stmt = $this->pdo->prepare(
                "UPDATE participant
                 SET nom = :nom,
                     prenom = :prenom,
                     email = :email,
                     pays = :pays,
                     langue_preferee = :langue_preferee,
                     temoignage = :temoignage,
                     date_inscription = :date_inscription
                 WHERE id = :id"
            );

            $stmt->execute([
                ':nom' => $participant->getNom(),
                ':prenom' => $participant->getPrenom(),
                ':email' => $participant->getEmail(),
                ':pays' => $participant->getPays(),
                ':langue_preferee' => $participant->getLanguePreferee(),
                ':temoignage' => $participant->getTemoignage(),
                ':date_inscription' => $participant->getDateInscription(),
                ':id' => $participant->getId()
            ]);

            return true;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la mise à jour du participant : " . $e->getMessage());
        }
    }

    public function deleteParticipant($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM participant WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la suppression du participant : " . $e->getMessage());
        }
    }
}

?>


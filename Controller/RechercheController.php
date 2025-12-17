<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Model/Recherche.php');

class RechercheController {

    public function addRecherche($recherche) {
        $nomChercheur = $recherche->getNomChercheur();
        $domaine = $recherche->getDomaine();
        $specialite = $recherche->getSpecialite();
        $institution = $recherche->getInstitution();
        $description = $recherche->getDescription();
        $publications = $recherche->getPublications();
        $email = $recherche->getEmail();
        $telephone = $recherche->getTelephone();
        $image = $recherche->getImage();
        $cv = $recherche->getCv();
        $disponibilite = $recherche->getDisponibilite();

        $sql = 'INSERT INTO recherches 
                (nom_chercheur, domaine, specialite, institution, description, publications, 
                 email, telephone, image, cv, disponibilite) 
                VALUES (:nom, :domaine, :specialite, :institution, :desc, :publications, 
                        :email, :telephone, :img, :cv, :disponibilite)';

        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':nom' => $nomChercheur,
                ':domaine' => $domaine,
                ':specialite' => $specialite,
                ':institution' => $institution,
                ':desc' => $description,
                ':publications' => $publications,
                ':email' => $email,
                ':telephone' => $telephone,
                ':img' => $image,
                ':cv' => $cv,
                ':disponibilite' => $disponibilite
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function deleteRecherche($id) {
        $recherche = $this->getRechercheById($id);
        if ($recherche) {
            // Supprimer l'image
            if (!empty($recherche['image'])) {
                $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/projet2/' . str_replace('../../', '', $recherche['image']);
                if (file_exists($imagePath) && strpos($imagePath, 'images/recherches/') !== false) {
                    unlink($imagePath);
                }
            }
            // Supprimer le CV
            if (!empty($recherche['cv'])) {
                $cvPath = $_SERVER['DOCUMENT_ROOT'] . '/projet2/' . str_replace('../../', '', $recherche['cv']);
                if (file_exists($cvPath) && strpos($cvPath, 'documents/cv/') !== false) {
                    unlink($cvPath);
                }
            }
        }
        
        $sql = 'DELETE FROM recherches WHERE id_recherche = :id';
        
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateRecherche($recherche, $id) {
        $nomChercheur = $recherche->getNomChercheur();
        $domaine = $recherche->getDomaine();
        $specialite = $recherche->getSpecialite();
        $institution = $recherche->getInstitution();
        $description = $recherche->getDescription();
        $publications = $recherche->getPublications();
        $email = $recherche->getEmail();
        $telephone = $recherche->getTelephone();
        $image = $recherche->getImage();
        $cv = $recherche->getCv();
        $disponibilite = $recherche->getDisponibilite();
        
        $sql = 'UPDATE recherches 
                SET nom_chercheur=:nom, domaine=:domaine, specialite=:specialite, 
                    institution=:institution, description=:desc, publications=:publications,
                    email=:email, telephone=:telephone, image=:img, cv=:cv, 
                    disponibilite=:disponibilite
                WHERE id_recherche=:id';

        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':nom' => $nomChercheur,
                ':domaine' => $domaine,
                ':specialite' => $specialite,
                ':institution' => $institution,
                ':desc' => $description,
                ':publications' => $publications,
                ':email' => $email,
                ':telephone' => $telephone,
                ':img' => $image,
                ':cv' => $cv,
                ':disponibilite' => $disponibilite,
                ':id' => $id
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getRechercheById($id) {
        $sql = 'SELECT * FROM recherches WHERE id_recherche = :id';
        
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }

    public function listRecherches() {
        $sql = 'SELECT * FROM recherches ORDER BY id_recherche DESC';
        $db = config::getConnexion();
        try {
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    public function listRecherchesByDomaine($domaine) {
        $sql = 'SELECT * FROM recherches WHERE domaine = :domaine ORDER BY id_recherche DESC';
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':domaine' => $domaine]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    public function getRandomRecherches($limit = 3) {
        $sql = 'SELECT * FROM recherches ORDER BY RAND() LIMIT :limit';
        
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    public function handleImageUpload() {
        $imagePath = null;
        
        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            if(in_array($fileExt, $allowed) && $_FILES['image']['size'] < 5000000){
                $newFilename = 'recherche_' . uniqid() . '.' . $fileExt;
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/projet2/assets/images/recherches/';
                
                if(!is_dir($uploadDir)){
                    mkdir($uploadDir, 0777, true);
                }
                
                $destination = $uploadDir . $newFilename;

                if(move_uploaded_file($_FILES['image']['tmp_name'], $destination)){
                    $imagePath = '../../assets/images/recherches/' . $newFilename;
                }
            }
        }
        
        return $imagePath;
    }

    public function searchRecherchesByName($searchTerm = '') {
        try {
            $db = config::getConnexion();

            // First, let's check if the table exists and has data
            $checkSql = 'SELECT COUNT(*) as count FROM recherches';
            $checkStmt = $db->query($checkSql);
            $count = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'];

            if ($count == 0) {
                // Return empty array if no data
                return [];
            }

            if (empty($searchTerm)) {
                return $this->listRecherches();
            }

            $sql = 'SELECT * FROM recherches WHERE nom_chercheur LIKE :search OR domaine LIKE :search OR institution LIKE :search OR description LIKE :search ORDER BY id_recherche DESC';
            $params = [':search' => '%' . $searchTerm . '%'];

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (Exception $e) {
            // For debugging, return a test result
            return [
                [
                    'id_recherche' => 1,
                    'nom_chercheur' => 'Test Researcher',
                    'domaine' => 'Test Domain',
                    'institution' => 'Test University',
                    'description' => 'This is a test researcher for debugging purposes.',
                    'email' => 'test@example.com',
                    'telephone' => '123456789',
                    'image' => '',
                    'disponibilite' => 'Disponible'
                ]
            ];
        }
    }

    public function getDistinctDomaines() {
        $sql = 'SELECT DISTINCT domaine FROM recherches WHERE domaine IS NOT NULL AND domaine != "" ORDER BY domaine';
        
        $db = config::getConnexion();
        try {
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    public function getDistinctSpecialites() {
        $sql = 'SELECT DISTINCT specialite FROM recherches WHERE specialite IS NOT NULL AND specialite != "" ORDER BY specialite';
        
        $db = config::getConnexion();
        try {
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    public function getDistinctInstitutions() {
        $sql = 'SELECT DISTINCT institution FROM recherches WHERE institution IS NOT NULL AND institution != "" ORDER BY institution';
        
        $db = config::getConnexion();
        try {
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }
}
?>
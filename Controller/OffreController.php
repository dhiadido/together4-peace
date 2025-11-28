<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Model/Offres.php');

class OffreController {

    public function addOffre($offre) {
        $nomSpecialiste = $offre->getNomSpecialiste();
        $description = $offre->getDescription();
        $prix = $offre->getPrix();
        $categorie = $offre->getCategorie();
        $categorieProbleme = $offre->getCategorieProbleme();
        $contact = $offre->getContact();
        $image = $offre->getImage();
        
        $sql = 'INSERT INTO offres_specialistes 
                (nom_specialiste, description, prix, categorie, categorie_probleme, contact, image, article) 
                VALUES (:nom, :desc, :prix, :cat, :cprob, :contact, :img, NULL)';

        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':nom' => $nomSpecialiste,
                ':desc' => $description,
                ':prix' => $prix,
                ':cat' => $categorie,
                ':cprob' => $categorieProbleme,
                ':contact' => $contact,
                ':img' => $image
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function deleteOffre($id) {
        $sql = 'DELETE FROM offres_specialistes WHERE id_offre = :id';
        
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function updateOffre($offre, $id) {
        $nomSpecialiste = $offre->getNomSpecialiste();
        $description = $offre->getDescription();
        $prix = $offre->getPrix();
        $categorie = $offre->getCategorie();
        $categorieProbleme = $offre->getCategorieProbleme();
        $contact = $offre->getContact();
        $image = $offre->getImage();
        
        $sql = 'UPDATE offres_specialistes 
                SET nom_specialiste=:nom, description=:desc, prix=:prix, categorie=:cat, 
                    categorie_probleme=:cprob, contact=:contact, image=:img 
                WHERE id_offre=:id';

        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':nom' => $nomSpecialiste,
                ':desc' => $description,
                ':prix' => $prix,
                ':cat' => $categorie,
                ':cprob' => $categorieProbleme,
                ':contact' => $contact,
                ':img' => $image,
                ':id' => $id
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getOffreById($id) {
        $sql = 'SELECT * FROM offres_specialistes WHERE id_offre = :id';
        
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

    public function listOffres() {
        $sql = 'SELECT * FROM offres_specialistes ORDER BY date_ajout DESC';
        
        $db = config::getConnexion();
        try {
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    public function showOffers($categorieProbleme, $limit = 12) {
        $sql = 'SELECT * FROM offres_specialistes WHERE categorie_probleme = :cat ORDER BY popularite DESC LIMIT :limit';
        
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':cat', $categorieProbleme, PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    public function handleImageUpload() {
        $imagePath = '../../assets/images/logo.png';
        
        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            if(in_array($fileExt, $allowed) && $_FILES['image']['size'] < 5000000){
                $newFilename = 'specialist_' . uniqid() . '.' . $fileExt;
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/projet2/assets/images/';
                
                if(!is_dir($uploadDir)){
                    mkdir($uploadDir, 0777, true);
                }
                
                $destination = $uploadDir . $newFilename;

                if(move_uploaded_file($_FILES['image']['tmp_name'], $destination)){
                    $imagePath = '../../assets/images/' . $newFilename;
                }
            }
        }
        
        return $imagePath;
    }
}


class Controller {

    // Render Frontoffice view
    public function view($view, $data = []) {
        // Pass $view to template.php
        $viewFile = __DIR__ . "/View/Frontoffice/" . $view . ".php";

        if (!file_exists($viewFile)) {
            die("View not found: $viewFile");
        }

        // Make $data available inside the template
        extract($data);

        // This variable is used by template.php to load the content
        $view = $view;

        // Load the main template (which will load the $view page)
        require __DIR__ . "/View/Frontoffice/template.php";
    }

    // Render Backoffice view
    public function viewAdmin($view, $data = []) {
        $viewFile = __DIR__ . "/View/Backoffice/" . $view . ".php";

        if (!file_exists($viewFile)) {
            die("Admin view not found: $viewFile");
        }

        extract($data);

        // Used by Backoffice template to load the view
        $view = $view;

        require __DIR__ . "/View/Backoffice/template.php";
    }
}

?>
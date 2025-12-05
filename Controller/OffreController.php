<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Model/Offres.php');

class OffreController {

    /* ----------------------
       ADD OFFRE
    -----------------------*/
    public function addOffre($offre) {
        $nomSpecialiste = $offre->getNomSpecialiste();
        $description = $offre->getDescription();
        $prix = $offre->getPrix();
        $categorie = $offre->getCategorie();
        $categorieProbleme = $offre->getCategorieProbleme();
        $contact = $offre->getContact();
        $image = $offre->getImage();
        $article_id = $offre->getArticleId();

        $sql = 'INSERT INTO offres_specialistes 
                (nom_specialiste, description, prix, categorie, categorie_probleme, contact, image, article) 
                VALUES (:nom, :desc, :prix, :cat, :cprob, :contact, :img, :article)';

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
                ':article' => $article_id
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }


    /* ----------------------
       DELETE OFFRE
    -----------------------*/
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


    /* ----------------------
       UPDATE OFFRE
    -----------------------*/
    public function updateOffre($offre, $id) {
        $nomSpecialiste = $offre->getNomSpecialiste();
        $description = $offre->getDescription();
        $prix = $offre->getPrix();
        $categorie = $offre->getCategorie();
        $categorieProbleme = $offre->getCategorieProbleme();
        $contact = $offre->getContact();
        $image = $offre->getImage();
        $article_id = $offre->getArticleId();
        
        $sql = 'UPDATE offres_specialistes 
                SET nom_specialiste=:nom, description=:desc, prix=:prix, categorie=:cat,
                    categorie_probleme=:cprob, contact=:contact, image=:img, article=:article
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
                ':article' => $article_id,
                ':id' => $id
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }


    /* ----------------------
       GET OFFRE BY ID
    -----------------------*/
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


    /* ----------------------
       GET OFFRE WITH ARTICLE (JOIN)
    -----------------------*/
    public function getOffreWithArticle($id_offre) {
        $sql = 'SELECT o.*, 
                       a.id_article, a.titre, a.theme, a.resume, 
                       a.contenu, a.image as article_image
                FROM offres_specialistes o
                LEFT JOIN articles a ON o.article = a.id_article
                WHERE o.id_offre = :id';
        
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id_offre]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }


    /* ----------------------
       LIST ALL OFFRES
    -----------------------*/
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


    /* ----------------------
       LIST ALL OFFRES WITH ARTICLES (JOIN)
    -----------------------*/
    public function listOffresWithArticles() {
        $sql = 'SELECT o.*, 
                       a.id_article, a.titre, a.theme, a.resume, 
                       a.contenu, a.image as article_image
                FROM offres_specialistes o
                LEFT JOIN articles a ON o.article = a.id_article
                ORDER BY o.date_ajout DESC';
        
        $db = config::getConnexion();
        try {
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }


    /* ----------------------
       SHOW OFFERS BY CATEGORY
    -----------------------*/
    public function showOffers($categorieProbleme, $limit = 12) {
        $sql = 'SELECT * FROM offres_specialistes 
                WHERE categorie_probleme = :cat 
                ORDER BY popularite DESC 
                LIMIT :limit';
        
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


    /* ----------------------
       SHOW OFFERS WITH ARTICLES BY CATEGORY (JOIN)
    -----------------------*/
    public function showOffersWithArticles($categorieProbleme, $limit = 12) {
        $sql = 'SELECT o.*, 
                       a.id_article, a.titre, a.theme, a.resume
                FROM offres_specialistes o
                LEFT JOIN articles a ON o.article = a.id_article
                WHERE o.categorie_probleme = :cat 
                ORDER BY o.popularite DESC 
                LIMIT :limit';
        
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


    /* ----------------------
       GET ARTICLE INFO FOR AN OFFRE
    -----------------------*/
    public function getArticleForOffre($id_offre) {
        $sql = 'SELECT a.* 
                FROM articles a
                INNER JOIN offres_specialistes o ON a.id_article = o.article
                WHERE o.id_offre = :id';
        
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id_offre]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }


    /* ----------------------
       IMAGE UPLOAD
    -----------------------*/
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


/* ----------------------
   CONTROLLER BASE CLASS
-----------------------*/
class Controller {

    public function view($view, $data = []) {
        $viewFile = __DIR__ . "/View/Frontoffice/" . $view . ".php";

        if (!file_exists($viewFile)) {
            die("View not found: $viewFile");
        }

        extract($data);

        $view = $view;

        require __DIR__ . "/View/Frontoffice/template.php";
    }

    public function viewAdmin($view, $data = []) {
        $viewFile = __DIR__ . "/View/Backoffice/" . $view . ".php";

        if (!file_exists($viewFile)) {
            die("Admin view not found: $viewFile");
        }

        extract($data);

        $view = $view;

        require __DIR__ . "/View/Backoffice/template.php";
    }
}

?>
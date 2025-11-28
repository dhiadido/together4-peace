<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Model/Article.php');

class ArticleController {

    /* ----------------------
       ADD ARTICLE
    -----------------------*/
    public function addArticle($article) {
        $titre = $article->getTitre();
        $theme = $article->getTheme();
        $resume = $article->getResume();
        $contenu = $article->getContenu();
        $image = $article->getImage();

        $sql = 'INSERT INTO articles (titre, theme, resume, contenu, image)
                VALUES (:titre, :theme, :resume, :contenu, :image)';

        $db = config::getConnexion();

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':titre' => $titre,
                ':theme' => $theme,
                ':resume' => $resume,
                ':contenu' => $contenu,
                ':image' => $image
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }


    /* ----------------------
       UPDATE ARTICLE
    -----------------------*/
    public function updateArticle($article, $id) {
        $titre = $article->getTitre();
        $theme = $article->getTheme();
        $resume = $article->getResume();
        $contenu = $article->getContenu();
        $image = $article->getImage();

        $sql = 'UPDATE articles 
                SET titre=:titre, theme=:theme, resume=:resume, 
                    contenu=:contenu, image=:image 
                WHERE id_article=:id';

        $db = config::getConnexion();

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':titre' => $titre,
                ':theme' => $theme,
                ':resume' => $resume,
                ':contenu' => $contenu,
                ':image' => $image,
                ':id' => $id
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }


    /* ----------------------
       DELETE ARTICLE
    -----------------------*/
    public function deleteArticle($id) {
        $sql = 'DELETE FROM articles WHERE id_article = :id';

        $db = config::getConnexion();

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }


    /* ----------------------
       GET ARTICLE BY ID
    -----------------------*/
    public function getArticleById($id) {
        $sql = 'SELECT * FROM articles WHERE id_article = :id';

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
       LIST ALL ARTICLES
    -----------------------*/
    public function listArticles() {
        $sql = 'SELECT * FROM articles ORDER BY id_article DESC';

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
       IMAGE UPLOAD (same style as OffreController)
    -----------------------*/
    public function handleImageUpload() {

        $imagePath = '../../assets/images/logo.png';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowed) && $_FILES['image']['size'] < 5000000) {

                $newName = 'article_' . uniqid() . '.' . $ext;
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/projet2/assets/images/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $destination = $uploadDir . $newName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $imagePath = '../../assets/images/' . $newName;
                }
            }
        }

        return $imagePath;
    }
}
?>

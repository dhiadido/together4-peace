<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Model/Article.php');

class ArticleController {

    public function addArticle($article) {
        $titre = $article->getTitre();
        $theme = $article->getTheme();
        $resume = $article->getResume();
        $contenu = $article->getContenu();
        $image = $article->getImage();

        $sql = 'INSERT INTO articles (titre, theme, resume, contenu, image, date_publication)
                VALUES (:titre, :theme, :resume, :contenu, :image, NOW())';

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

    public function deleteArticle($id) {
        // Récupérer l'image avant suppression pour la supprimer du serveur
        $article = $this->getArticleById($id);
        if ($article && !empty($article['image'])) {
            $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/projet2/' . str_replace('../../', '', $article['image']);
            if (file_exists($imagePath) && strpos($imagePath, 'images/articles/') !== false) {
                unlink($imagePath);
            }
        }
        
        $sql = 'DELETE FROM articles WHERE id_article = :id';

        $db = config::getConnexion();

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

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

    public function getArticleWithOffres($id_article) {
        $sql = 'SELECT a.*, 
                       o.id_offre, o.nom_specialiste, o.description as offre_description,
                       o.prix, o.categorie, o.categorie_probleme, o.contact, 
                       o.image as offre_image, o.popularite, o.date_ajout,
                       DATEDIFF(NOW(), o.date_ajout) as offre_days_old
                FROM articles a
                LEFT JOIN offres_specialistes o ON a.id_article = o.article
                WHERE a.id_article = :id';

        $db = config::getConnexion();

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id_article]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($results)) {
                return null;
            }

            $article = [
                'id_article' => $results[0]['id_article'],
                'titre' => $results[0]['titre'],
                'theme' => $results[0]['theme'],
                'resume' => $results[0]['resume'],
                'contenu' => $results[0]['contenu'],
                'image' => $results[0]['image'],
                'date_publication' => $results[0]['date_publication'],
                'offres' => []
            ];

            foreach ($results as $row) {
                if ($row['id_offre']) {
                    $article['offres'][] = [
                        'id_offre' => $row['id_offre'],
                        'nom_specialiste' => $row['nom_specialiste'],
                        'description' => $row['offre_description'],
                        'prix' => $row['prix'],
                        'categorie' => $row['categorie'],
                        'categorie_probleme' => $row['categorie_probleme'],
                        'contact' => $row['contact'],
                        'image' => $row['offre_image'],
                        'popularite' => $row['popularite'],
                        'date_ajout' => $row['date_ajout'],
                        'days_old' => $row['offre_days_old']
                    ];
                }
            }

            return $article;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }

    public function listArticles() {
        $sql = 'SELECT *, DATEDIFF(NOW(), date_publication) as days_old 
                FROM articles 
                ORDER BY date_publication DESC';

        $db = config::getConnexion();

        try {
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    public function listArticlesWithOffres() {
        $sql = 'SELECT a.*, 
                       DATEDIFF(NOW(), a.date_publication) as article_days_old,
                       o.id_offre, o.nom_specialiste, o.description as offre_description,
                       o.prix, o.categorie, o.categorie_probleme, o.contact, 
                       o.image as offre_image, o.popularite, o.date_ajout,
                       DATEDIFF(NOW(), o.date_ajout) as offre_days_old
                FROM articles a
                LEFT JOIN offres_specialistes o ON a.id_article = o.article
                ORDER BY a.date_publication DESC';

        $db = config::getConnexion();

        try {
            $stmt = $db->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $articles = [];
            
            foreach ($results as $row) {
                $articleId = $row['id_article'];
                
                if (!isset($articles[$articleId])) {
                    $articles[$articleId] = [
                        'id_article' => $row['id_article'],
                        'titre' => $row['titre'],
                        'theme' => $row['theme'],
                        'resume' => $row['resume'],
                        'contenu' => $row['contenu'],
                        'image' => $row['image'],
                        'date_publication' => $row['date_publication'],
                        'days_old' => $row['article_days_old'],
                        'offres' => []
                    ];
                }
                
                if ($row['id_offre']) {
                    $articles[$articleId]['offres'][] = [
                        'id_offre' => $row['id_offre'],
                        'nom_specialiste' => $row['nom_specialiste'],
                        'description' => $row['offre_description'],
                        'prix' => $row['prix'],
                        'categorie' => $row['categorie'],
                        'categorie_probleme' => $row['categorie_probleme'],
                        'contact' => $row['contact'],
                        'image' => $row['offre_image'],
                        'popularite' => $row['popularite'],
                        'date_ajout' => $row['date_ajout'],
                        'days_old' => $row['offre_days_old']
                    ];
                }
            }

            return array_values($articles);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    public function getSmartRecommendations($quizScore) {
        $sql = 'SELECT a.*, 
                       DATEDIFF(NOW(), a.date_publication) as article_days_old,
                       COUNT(o.id_offre) as offre_count,
                       AVG(o.popularite) as avg_popularity,
                       MIN(o.prix) as min_price,
                       MAX(o.prix) as max_price,
                       AVG(DATEDIFF(NOW(), o.date_ajout)) as avg_offre_age
                FROM articles a
                LEFT JOIN offres_specialistes o ON a.id_article = o.article
                GROUP BY a.id_article
                HAVING offre_count > 0
                ORDER BY a.date_publication DESC';

        $db = config::getConnexion();

        try {
            $stmt = $db->query($sql);
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($articles as &$article) {
                $article['recommendation_score'] = $this->calculateRecommendationScore(
                    $quizScore,
                    $article
                );
            }

            usort($articles, function($a, $b) {
                return $b['recommendation_score'] <=> $a['recommendation_score'];
            });

            $topArticles = array_slice($articles, 0, 5);
            $detailedArticles = [];

            foreach ($topArticles as $article) {
                $detailedArticle = $this->getArticleWithOffres($article['id_article']);
                if ($detailedArticle) {
                    $detailedArticle['recommendation_score'] = $article['recommendation_score'];
                    $detailedArticle['offre_count'] = $article['offre_count'];
                    $detailedArticle['avg_popularity'] = $article['avg_popularity'];
                    $detailedArticle['min_price'] = $article['min_price'];
                    $detailedArticle['days_old'] = $article['article_days_old'];
                    
                    $detailedArticle['offres'] = $this->rankOffres(
                        $detailedArticle['offres'],
                        $quizScore
                    );
                    
                    $detailedArticles[] = $detailedArticle;
                }
            }

            return $detailedArticles;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    private function calculateRecommendationScore($quizScore, $article) {
        $score = 0;

        $themeScore = $this->getThemeRelevance($article['theme'], $quizScore);
        $score += $themeScore * 35;

        $offreCount = (int)$article['offre_count'];
        if ($offreCount >= 5) {
            $score += 20;
        } elseif ($offreCount >= 3) {
            $score += 15;
        } elseif ($offreCount >= 1) {
            $score += 10;
        }

        $minPrice = (float)$article['min_price'];
        if ($quizScore < 40) {
            if ($minPrice <= 50) {
                $score += 15;
            } elseif ($minPrice <= 100) {
                $score += 10;
            } else {
                $score += 5;
            }
        } else {
            $score += 12;
        }

        $popularity = (float)($article['avg_popularity'] ?? 0);
        $score += min(10, $popularity * 2);

        $daysOld = (int)($article['article_days_old'] ?? 999);
        if ($daysOld <= 7) {
            $score += 20;
        } elseif ($daysOld <= 30) {
            $score += 15;
        } elseif ($daysOld <= 90) {
            $score += 10;
        } elseif ($daysOld <= 180) {
            $score += 5;
        }
        
        $avgOffreAge = (float)($article['avg_offre_age'] ?? 999);
        if ($avgOffreAge <= 14) {
            $score += 5;
        }

        return round($score, 2);
    }

    private function getThemeRelevance($theme, $quizScore) {
        $theme = strtolower(trim($theme));
        
        $criticalThemes = [
            'violence', 'conflit', 'urgence', 'crise',
            'trauma', 'abus', 'harcèlement', 'guerre'
        ];
        
        $importantThemes = [
            'médiation', 'psychologie', 'anxiété', 'stress',
            'famille', 'relation', 'communication', 'inclusion'
        ];
        
        $educationalThemes = [
            'éducation', 'paix', 'société', 'prévention',
            'sensibilisation', 'tolérance', 'dialogue'
        ];

        if ($quizScore < 40) {
            foreach ($criticalThemes as $critical) {
                if (stripos($theme, $critical) !== false) {
                    return 1.0;
                }
            }
            foreach ($importantThemes as $important) {
                if (stripos($theme, $important) !== false) {
                    return 0.8;
                }
            }
            foreach ($educationalThemes as $educational) {
                if (stripos($theme, $educational) !== false) {
                    return 0.6;
                }
            }
            return 0.5;
        } else {
            foreach ($educationalThemes as $educational) {
                if (stripos($theme, $educational) !== false) {
                    return 0.9;
                }
            }
            foreach ($importantThemes as $important) {
                if (stripos($theme, $important) !== false) {
                    return 0.7;
                }
            }
            return 0.6;
        }
    }


    private function rankOffres($offres, $quizScore) {
        foreach ($offres as &$offre) {
            $score = 0;
            
            $score += (float)($offre['popularite'] ?? 0) * 3;

            if ($quizScore < 40) {
                if ($offre['prix'] <= 50) {
                    $score += 20;
                } elseif ($offre['prix'] <= 100) {
                    $score += 10;
                }
            } else {
                $score += 10;
            }
            
            $category = strtolower($offre['categorie'] ?? '');
            if (stripos($category, 'psychologue') !== false || 
                stripos($category, 'médiateur') !== false) {
                $score += 15;
            }
            
            $daysOld = (int)($offre['days_old'] ?? 999);
            if ($daysOld <= 7) {
                $score += 15;
            } elseif ($daysOld <= 30) {
                $score += 10;
            } elseif ($daysOld <= 90) {
                $score += 5;
            }
            
            $offre['relevance_score'] = $score;
        }
        
        usort($offres, function($a, $b) {
            return $b['relevance_score'] <=> $a['relevance_score'];
        });
        
        return $offres;
    }

    public function trackOffreClick($offreId) {
        $sql = 'UPDATE offres_specialistes 
                SET popularite = popularite + 1 
                WHERE id_offre = :id';
        
        $db = config::getConnexion();
        
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $offreId]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getOffresByArticle($id_article) {
        $sql = "SELECT *, DATEDIFF(NOW(), date_ajout) as days_old 
                FROM offres_specialistes 
                WHERE article = :id
                ORDER BY date_ajout DESC";

        $db = config::getConnexion();

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id_article]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    public function countOffresByArticle($id_article) {
        $sql = "SELECT COUNT(*) as total FROM offres_specialistes WHERE article = :id";

        $db = config::getConnexion();

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id_article]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return 0;
        }
    }

    public function handleImageUpload() {
        // Retourne NULL si aucune image n'est uploadée (pas d'image par défaut)
        $imagePath = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowed) && $_FILES['image']['size'] < 5000000) {
                $newName = 'article_' . uniqid() . '.' . $ext;
                // DOSSIER SÉPARÉ POUR LES ARTICLES
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/projet2/assets/images/articles/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $destination = $uploadDir . $newName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $imagePath = '../../assets/images/articles/' . $newName;
                }
            }
        }

        return $imagePath;
    }

    public static function timeAgo($daysOld) {
        if ($daysOld == 0) return "Aujourd'hui";
        if ($daysOld == 1) return "Hier";
        if ($daysOld <= 7) return "Il y a " . $daysOld . " jours";
        if ($daysOld <= 30) return "Il y a " . ceil($daysOld/7) . " semaines";
        if ($daysOld <= 365) return "Il y a " . ceil($daysOld/30) . " mois";
        return "Il y a " . ceil($daysOld/365) . " ans";
    }

    public function searchArticlesByTerm($searchTerm = '') {
        try {
            $db = config::getConnexion();

            if (empty($searchTerm)) {
                return $this->listArticlesWithOffres();
            }

            $sql = 'SELECT * FROM articles WHERE titre LIKE :search OR theme LIKE :search OR resume LIKE :search OR contenu LIKE :search ORDER BY date_publication DESC';
            $params = [':search' => '%' . $searchTerm . '%'];

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Add offres to articles
            foreach ($articles as &$article) {
                $article['offres'] = $this->getOffresByArticle($article['id_article']);
            }

            return $articles;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }
}
?>
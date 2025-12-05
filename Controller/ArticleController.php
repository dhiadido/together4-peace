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
       GET ARTICLE WITH OFFRES (JOIN)
    -----------------------*/
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

            // Structure the data: article + its offres
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

            // Add all offres to the article
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


    /* ----------------------
       LIST ALL ARTICLES
    -----------------------*/
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


    /* ----------------------
       LIST ALL ARTICLES WITH THEIR OFFRES (JOIN)
    -----------------------*/
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
                
                // If article not yet in array, add it
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
                
                // Add offre if it exists
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


    /* ----------------------
       🔥 SMART RECOMMENDATION SYSTEM 🔥
       NOW WITH DATE BOOSTING!
    -----------------------*/
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

            // Calculate recommendation score for each article
            foreach ($articles as &$article) {
                $article['recommendation_score'] = $this->calculateRecommendationScore(
                    $quizScore,
                    $article
                );
            }

            // Sort by recommendation score (highest first)
            usort($articles, function($a, $b) {
                return $b['recommendation_score'] <=> $a['recommendation_score'];
            });

            // Get full details with offres for top articles
            $topArticles = array_slice($articles, 0, 5); // Top 5 recommended
            $detailedArticles = [];

            foreach ($topArticles as $article) {
                $detailedArticle = $this->getArticleWithOffres($article['id_article']);
                if ($detailedArticle) {
                    $detailedArticle['recommendation_score'] = $article['recommendation_score'];
                    $detailedArticle['offre_count'] = $article['offre_count'];
                    $detailedArticle['avg_popularity'] = $article['avg_popularity'];
                    $detailedArticle['min_price'] = $article['min_price'];
                    $detailedArticle['days_old'] = $article['article_days_old'];
                    
                    // Rank offres within article
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


    /* ----------------------
       CALCULATE RECOMMENDATION SCORE
       NOW WITH RECENCY BONUS! 🆕
    -----------------------*/
    private function calculateRecommendationScore($quizScore, $article) {
        $score = 0;

        // 1. THEME RELEVANCE (35 points max - reduced to make room for recency)
        $themeScore = $this->getThemeRelevance($article['theme'], $quizScore);
        $score += $themeScore * 35;

        // 2. OFFRE AVAILABILITY (20 points max)
        $offreCount = (int)$article['offre_count'];
        if ($offreCount >= 5) {
            $score += 20;
        } elseif ($offreCount >= 3) {
            $score += 15;
        } elseif ($offreCount >= 1) {
            $score += 10;
        }

        // 3. PRICE AFFORDABILITY (15 points max)
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

        // 4. POPULARITY (10 points max)
        $popularity = (float)($article['avg_popularity'] ?? 0);
        $score += min(10, $popularity * 2);

        // 5. 🆕 RECENCY BONUS (20 points max - NEW!)
        $daysOld = (int)($article['article_days_old'] ?? 999);
        if ($daysOld <= 7) {
            $score += 20; // Brand new
        } elseif ($daysOld <= 30) {
            $score += 15; // Recent
        } elseif ($daysOld <= 90) {
            $score += 10; // Still fresh
        } elseif ($daysOld <= 180) {
            $score += 5; // Older but OK
        }
        
        // Bonus if offres are also recent
        $avgOffreAge = (float)($article['avg_offre_age'] ?? 999);
        if ($avgOffreAge <= 14) {
            $score += 5; // New specialists available
        }

        return round($score, 2);
    }


    /* ----------------------
       THEME RELEVANCE SCORING
    -----------------------*/
    private function getThemeRelevance($theme, $quizScore) {
        $theme = strtolower(trim($theme));
        
        // High priority themes for low scores
        $criticalThemes = [
            'violence', 'conflit', 'urgence', 'crise',
            'trauma', 'abus', 'harcèlement', 'guerre'
        ];
        
        // Medium priority
        $importantThemes = [
            'médiation', 'psychologie', 'anxiété', 'stress',
            'famille', 'relation', 'communication', 'inclusion'
        ];
        
        // Educational themes
        $educationalThemes = [
            'éducation', 'paix', 'société', 'prévention',
            'sensibilisation', 'tolérance', 'dialogue'
        ];

        if ($quizScore < 40) {
            // Low score: prioritize critical themes
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
            // Higher score: educational themes are good
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


    /* ----------------------
       RANK OFFRES WITHIN ARTICLE
       NOW WITH DATE PRIORITY! 🆕
    -----------------------*/
    private function rankOffres($offres, $quizScore) {
        foreach ($offres as &$offre) {
            $score = 0;
            
            // Popularity factor
            $score += (float)($offre['popularite'] ?? 0) * 3;
            
            // Price factor
            if ($quizScore < 40) {
                if ($offre['prix'] <= 50) {
                    $score += 20;
                } elseif ($offre['prix'] <= 100) {
                    $score += 10;
                }
            } else {
                $score += 10;
            }
            
            // Category bonus
            $category = strtolower($offre['categorie'] ?? '');
            if (stripos($category, 'psychologue') !== false || 
                stripos($category, 'médiateur') !== false) {
                $score += 15;
            }
            
            // 🆕 RECENCY BONUS
            $daysOld = (int)($offre['days_old'] ?? 999);
            if ($daysOld <= 7) {
                $score += 15; // Very new
            } elseif ($daysOld <= 30) {
                $score += 10; // Recent
            } elseif ($daysOld <= 90) {
                $score += 5; // Still OK
            }
            
            $offre['relevance_score'] = $score;
        }
        
        // Sort by relevance
        usort($offres, function($a, $b) {
            return $b['relevance_score'] <=> $a['relevance_score'];
        });
        
        return $offres;
    }


    /* ----------------------
       TRACK OFFRE CLICK
    -----------------------*/
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


    /* ----------------------
       GET OFFRES BY ARTICLE
    -----------------------*/
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


    /* ----------------------
       COUNT OFFRES FOR AN ARTICLE
    -----------------------*/
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


    /* ----------------------
       IMAGE UPLOAD
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


    /* ----------------------
       🆕 HELPER: Format Time Ago
    -----------------------*/
    public static function timeAgo($daysOld) {
        if ($daysOld == 0) return "Aujourd'hui";
        if ($daysOld == 1) return "Hier";
        if ($daysOld <= 7) return "Il y a " . $daysOld . " jours";
        if ($daysOld <= 30) return "Il y a " . ceil($daysOld/7) . " semaines";
        if ($daysOld <= 365) return "Il y a " . ceil($daysOld/30) . " mois";
        return "Il y a " . ceil($daysOld/365) . " ans";
    }
}
?>
<?php
require_once(__DIR__ . '/../../Controller/RechercheController.php');

$controller = new RechercheController();

// Récupérer le terme de recherche depuis GET
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Effectuer la recherche
$recherches = $controller->searchRecherchesByName($searchTerm);

// Fonction pour mettre en évidence le terme de recherche
function highlightSearchTerm($text, $searchTerm) {
    if (empty($searchTerm)) {
        return htmlspecialchars($text);
    }
    $highlighted = preg_replace('/(' . preg_quote($searchTerm, '/') . ')/i', '<mark>$1</mark>', htmlspecialchars($text));
    return $highlighted;
}

// Inclure le template
include "templateFront.php";
?>

<div class="recherches-container" style="padding: 40px 20px; max-width: 1400px; margin: 0 auto;">
    <h1 style="text-align: center; color: #2c3e50; margin-bottom: 30px; font-size: 2.5em;">
        <i class="fa fa-graduation-cap"></i> Nos Chercheurs Experts
    </h1>

    <!-- Widget de recherche simple -->
    <div class="search-widget" style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 40px; text-align: center;">
        <h2 style="color: #2c3e50; margin-bottom: 20px; font-size: 1.5em;">
            <i class="fa fa-search"></i> Rechercher un Chercheur
        </h2>
        <form method="GET" action="Recherches.php" style="max-width: 500px; margin: 0 auto; display: flex; gap: 10px;">
            <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>"
                   placeholder="Entrez le nom du chercheur..."
                   style="flex: 1; padding: 12px; border: 2px solid #667eea; border-radius: 25px; font-size: 16px; outline: none;">
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 12px 25px; border-radius: 25px; cursor: pointer; font-weight: 600; transition: transform 0.2s ease;">
                <i class="fa fa-search"></i> Rechercher
            </button>
        </form>
        <?php if (!empty($searchTerm)): ?>
            <p style="margin-top: 15px; color: #666;">
                Résultats pour "<strong><?= htmlspecialchars($searchTerm) ?></strong>"
                <a href="Recherches.php" style="color: #667eea; text-decoration: none; margin-left: 10px;">
                    <i class="fa fa-times"></i> Effacer
                </a>
            </p>
        <?php endif; ?>
    </div>

    <!-- Résultats -->
    <?php if (empty($recherches)): ?>
        <div class="no-results" style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <i class="fas fa-search" style="font-size: 48px; color: #6c757d; margin-bottom: 20px;"></i>
            <h3 style="color: #6c757d; margin-bottom: 10px;">
                <?php if (!empty($searchTerm)): ?>
                    Aucun chercheur trouvé
                <?php else: ?>
                    Aucun chercheur disponible
                <?php endif; ?>
            </h3>
            <p style="color: #6c757d;">
                <?php if (!empty($searchTerm)): ?>
                    Essayez un autre nom ou consultez tous nos chercheurs.
                <?php else: ?>
                    Revenez plus tard pour découvrir nos experts.
                <?php endif; ?>
            </p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 25px; margin-top: 30px;">
            <?php foreach($recherches as $r): ?>
                <?php
                $name = htmlspecialchars($r['nom_chercheur']);
                $domain = htmlspecialchars($r['domaine']);
                $inst = htmlspecialchars($r['institution']);
                $desc = htmlspecialchars(strlen($r['description']) > 150 ? substr($r['description'], 0, 150) . '...' : $r['description']);
                $email = htmlspecialchars($r['email']);
                $id = intval($r['id_recherche']);
                $image = !empty($r['image']) ? htmlspecialchars($r['image']) : null;
                $dispo = htmlspecialchars($r['disponibilite']);
                ?>

                <div class="chercheur-card" style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: transform 0.3s ease, box-shadow 0.3s ease; position: relative;">
                    <?php if ($image && file_exists($_SERVER['DOCUMENT_ROOT'] . '/projet2/' . str_replace('../../', '', $image))): ?>
                        <img src="<?= $image ?>"
                             alt="<?= $name ?>"
                             style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 15px;">
                    <?php else: ?>
                        <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 64px;">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    <?php endif; ?>

                    <span class="disponibilite-badge <?= strtolower($dispo) ?>" style="position: absolute; top: 15px; right: 15px; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                        <?= $dispo ?>
                    </span>

                    <h3 style="color: #2c3e50; margin: 15px 0; font-size: 1.4em;"><?= highlightSearchTerm($r['nom_chercheur'], $searchTerm) ?></h3>
                    <p style="color: #667eea; margin: 5px 0; font-weight: 600;"><i class="fa fa-microscope"></i> <?= highlightSearchTerm($r['domaine'], $searchTerm) ?></p>
                    <p style="color: #6c757d; margin: 5px 0; font-size: 14px;"><i class="fa fa-university"></i> <?= highlightSearchTerm($r['institution'], $searchTerm) ?></p>
                    <p style="color: #7f8c8d; margin: 15px 0; line-height: 1.6; min-height: 60px;"><?= highlightSearchTerm(strlen($r['description']) > 150 ? substr($r['description'], 0, 150) . '...' : $r['description'], $searchTerm) ?></p>

                    <div style="margin-top: 20px; display: flex; gap: 10px;">
                        <a href="ShowRecherche.php?id=<?= $id ?>" style="flex: 1; text-decoration: none;">
                            <button style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: transform 0.2s ease;">
                                <i class="fa fa-eye"></i> Voir Profil
                            </button>
                        </a>
                        <a href="mailto:<?= $email ?>" style="text-decoration: none;">
                            <button style="background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: transform 0.2s ease;">
                                <i class="fa fa-envelope"></i>
                            </button>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.chercheur-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.2) !important;
}

.disponibilite-badge.disponible {
    background: #d4edda;
    color: #155724;
}

.disponibilite-badge.occupé {
    background: #f8d7da;
    color: #721c24;
}

.disponibilite-badge.en {
    background: #fff3cd;
    color: #856404;
}

.disponibilite-badge.demande {
    background: #fff3cd;
    color: #856404;
}

.search-widget input:focus {
    border-color: #764ba2;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.search-widget button:hover {
    transform: scale(1.05);
}

mark {
    background-color: #fff3cd;
    color: #856404;
    padding: 2px 4px;
    border-radius: 3px;
    font-weight: bold;
}

@media (max-width: 768px) {
    .recherches-container h1 {
        font-size: 1.8em !important;
    }

    .search-widget form {
        flex-direction: column;
    }

    .recherches-container > div:last-child {
        grid-template-columns: 1fr !important;
    }
}
</style>


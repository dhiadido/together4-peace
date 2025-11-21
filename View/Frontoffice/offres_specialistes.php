<?php
// Simple endpoint qui retourne des cartes HTML d'offres selon un score donné.
// IMPORTANT: Ce fichier est volontairement simple pour un projet étudiant.
header('Content-Type: text/html; charset=utf-8');

$score = isset($_GET['score']) ? intval($_GET['score']) : 0;
// On peut imaginer récupérer la catégorie depuis les questions ratées.
$categorie_probleme = isset($_GET['cat']) ? $_GET['cat'] : 'stress';

// Connexion via la classe config
require_once(__DIR__ . '/../../config.php'); // Adaptez le chemin si config.php n'est pas dans le même dossier

try {
    $pdo = config::getConnexion();
} catch (Exception $e) {
    echo '<div class="card"><p>Impossible de se connecter à la base de données : ' . htmlspecialchars($e->getMessage()) . '</p></div>';
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM offres_specialistes WHERE categorie_probleme = :cat ORDER BY popularite DESC LIMIT 12');
$stmt->execute([':cat'=>$categorie_probleme]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(!$rows){
    echo '<div class="card"><p>Aucune offre trouvée pour cette catégorie.</p></div>';
    exit;
}

foreach($rows as $r){
    $img = htmlspecialchars($r['image'] ?: 'image.png');
    $name = htmlspecialchars($r['nom_specialiste']);
    $desc = htmlspecialchars(strlen($r['description'])>180 ? substr($r['description'],0,180).'...' : $r['description']);
    $price = number_format($r['prix'],2);
    $contact = htmlspecialchars($r['contact']);
    $id = intval($r['id_offre']);

    echo "<div class='card'>
        <img src='..\..\assets\sami ben ali.jpg' alt='$name' style='height: 40px' />
        <h3>$name</h3>
        <p class='meta'>$desc</p>
        <p><strong>Prix:</strong> $price DT</p>
        <p><strong>Contact:</strong> $contact</p>
        <a href='mailto:$contact' style='display:block; text-align:center; margin-top:10px;'>
          <button>Contacter</button>
        </a>
    </div>";
}
?>
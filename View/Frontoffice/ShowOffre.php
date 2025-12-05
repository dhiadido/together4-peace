<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Together4Peace - Bâtir des Ponts. Non des Murs.</title>
    <link rel="stylesheet" href="..\..\assets\css\styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="offers-container" align="center">
    <h1>Offres Recommandées pour Vous</h1>
</body>
</html>
<?php
header('Content-Type: text/html; charset=utf-8');


require_once(__DIR__ . '/../../Controller/OffreController.php');

$score = isset($_GET['score']) ? intval($_GET['score']) : 0;
$categorie_probleme = isset($_GET['cat']) ? $_GET['cat'] : 'stress';

$controller = new OffreController();
$rows = $controller->showOffers($categorie_probleme, 12);

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

    echo "<div class='card' align='center'>
        <img src='..\..\assets\images\logo.png' alt='$name' style='height: 40px' />
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
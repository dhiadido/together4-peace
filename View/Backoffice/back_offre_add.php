<?php
require_once(__DIR__ . '/../../config.php');
$pdo = config::getConnexion();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $imagePath = 'assets/placeholder.png'; // Image par défaut
  
  // Gérer l'upload de l'image
  if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    
    if(in_array($fileExt, $allowed) && $_FILES['image']['size'] < 5000000){
      
      $newFilename = 'specialist_' . uniqid() . '.' . $fileExt;
      
      // Dossier réel dans ton projet
      $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/projet - copie/assets/';
      
      if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0777, true);
      }
      
      $destination = $uploadDir . $newFilename;

      if(move_uploaded_file($_FILES['image']['tmp_name'], $destination)){
        $imagePath = 'assets/' . $newFilename;
      }
    }
  }
  
  $sql = 'INSERT INTO offres_specialistes 
          (nom_specialiste, description, prix, categorie, categorie_probleme, contact, image) 
          VALUES (:nom,:desc,:prix,:cat,:cprob,:contact,:img)';

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':nom'=>$_POST['nom'],
    ':desc'=>$_POST['description'],
    ':prix'=>floatval($_POST['prix']),
    ':cat'=>$_POST['categorie'],
    ':cprob'=>$_POST['categorie_probleme'],
    ':contact'=>$_POST['contact'],
    ':img'=>$imagePath
  ]);

  header('Location: back_offre_list.php');
  exit;
}

// → On charge ton template
include "template.php"; 
?>

<h2 class="mb-4">Ajouter une offre</h2>

<form method="post" enctype="multipart/form-data" class="card p-4 shadow">

    <div class="mb-3">
        <label class="form-label">Nom</label>
        <input name="nom" type="text" class="form-control" placeholder="Ex: Dr. Ahmed Ben Salah" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control" placeholder="Décrivez les services offerts..."></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Prix</label>
        <input name="prix" type="number" step="0.01" class="form-control" placeholder="Ex: 50.00">
    </div>

    <div class="mb-3">
        <label class="form-label">Catégorie</label>
        <input name="categorie" type="text" class="form-control" placeholder="Ex: Psychologue, Coach...">
    </div>

    <div class="mb-3">
        <label class="form-label">Catégorie problème</label>
        <input name="categorie_probleme" type="text" class="form-control" placeholder="Ex: stress, anxiété...">
    </div>

    <div class="mb-3">
        <label class="form-label">Contact</label>
        <input name="contact" type="text" class="form-control" placeholder="email ou numéro">
    </div>

    <div class="mb-3">
        <label class="form-label">Photo du spécialiste</label>
        <input name="image" type="file" id="imageInput" accept="image/*" class="form-control">
        <small class="text-muted">Formats acceptés : JPG, PNG, GIF, WEBP</small>

        <div class="mt-3" id="imagePreview"></div>
    </div>

    <button type="submit" class="btn btn-success">Créer</button>

</form>

<script src="add.js"></script>

</div>
</body>
</html>

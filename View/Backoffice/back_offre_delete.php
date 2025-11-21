<?php
require_once(__DIR__ . '/../../config.php');
$pdo = config::getConnexion();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id){
  $stmt = $pdo->prepare('DELETE FROM offres_specialistes WHERE id_offre = :id');
  $stmt->execute([':id'=>$id]);
}
header('Location: back_offre_list.php');
exit;
?>
<?php
// Script de test pour vérifier l'URI de redirection
require_once '../config/GoogleConfig.php';

echo "<h2>URI de redirection générée :</h2>";
echo "<p style='font-family: monospace; background: #f0f0f0; padding: 10px;'>";
echo htmlspecialchars(GoogleConfig::getRedirectUri());
echo "</p>";

echo "<h2>Informations du serveur :</h2>";
echo "<pre>";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "HTTPS: " . (isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'non défini') . "\n";
echo "</pre>";

echo "<h2>Instructions :</h2>";
echo "<p>Copiez l'URI de redirection ci-dessus et ajoutez-la dans Google Cloud Console :</p>";
echo "<ol>";
echo "<li>Allez sur <a href='https://console.cloud.google.com/' target='_blank'>Google Cloud Console</a></li>";
echo "<li>Naviguez vers : APIs & Services > Identifiants</li>";
echo "<li>Sélectionnez votre ID client OAuth 2.0</li>";
echo "<li>Dans 'URIs de redirection autorisés', ajoutez l'URI ci-dessus</li>";
echo "<li>Assurez-vous que l'URI correspond EXACTEMENT (pas d'espace, même protocole http/https)</li>";
echo "</ol>";
?>


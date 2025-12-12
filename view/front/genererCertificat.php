<?php  
// Fichier: view/front/genererCertificat.php
require __DIR__ . '/../../lib/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../../lib/phpmailer/src/SMTP.php';
require __DIR__ . '/../../lib/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 1. Récupération des données (GET ou POST) et sanitation
$id_quiz          = isset($_REQUEST['quiz_id']) ? htmlspecialchars($_REQUEST['quiz_id']) : 0;
$score_pourcentage = isset($_REQUEST['score']) ? htmlspecialchars($_REQUEST['score']) : 0;
$titre_quiz       = isset($_REQUEST['quiz_titre']) ? htmlspecialchars($_REQUEST['quiz_titre']) : 'Quiz de Sensibilisation'; 
$email_utilisateur = isset($_REQUEST['email']) ? filter_var(trim($_REQUEST['email']), FILTER_SANITIZE_EMAIL) : '';

$nom_utilisateur = '';
if (!empty($_REQUEST['user'])) {
    $nom_utilisateur = $_REQUEST['user'];
} elseif (!empty($_REQUEST['nom'])) {
    $nom_utilisateur = $_REQUEST['nom'];
}
$nom_utilisateur = trim(strip_tags($nom_utilisateur));
$nom_utilisateur = mb_substr($nom_utilisateur, 0, 80);
if ($nom_utilisateur === '') {
    $nom_utilisateur = 'Invité';
}

$date_obtention   = date('d F Y'); 

// Debug comment to check incoming email (in page source)
echo "<!-- Email utilisé pour envoi : " . htmlspecialchars($email_utilisateur) . " -->\n";

// 2. Buffer output certificate HTML
ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Certificat de Réussite</title>
    
    <style>
        @page { size: A4 landscape; margin: 0; } 
        body { 
            font-family: 'Arial', sans-serif; 
            margin: 0; 
            padding: 0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh;
            background-color: #ddd; 
        }
        .certificat-box {
            width: 29.7cm; 
            height: 21cm; 
            border: 20px solid #f1c40f; 
            padding: 30px; 
            box-sizing: border-box;
            background-color: #fff;
            text-align: center;
            display: flex; 
            flex-direction: column;
            align-items: center;
            position: relative;
        }
        .logo-container {
            position: absolute;
            top: 40px; 
            left: 40px; 
            width: 150px; 
        }
        .logo-container img { width: 100%; height: auto; }
        .content-container { width: 100%; text-align: center; padding-top: 50px; }
        h1 { color: #e67e22; font-size: 3em; margin-top: 0; margin-bottom: 20px; }
        h2 { color: #34495e; font-size: 1.5em; margin-bottom: 40px; }
        .nom-certifie { color: #2ecc71; font-size: 2.5em; margin: 20px 0; border-bottom: 3px double #ccc; display: inline-block; padding-bottom: 5px; }
        .message { font-size: 1.2em; margin-top: 20px; line-height: 1.5; }
        .signature { margin-top: 80px; display: flex; justify-content: space-around; width: 80%; }
        .signature div { border-top: 1px solid #000; padding-top: 10px; width: 30%; }
        @media print {
            body { min-height: auto; background-color: #fff; }
            .certificat-box { box-shadow: none; border-color: #f1c40f; }
            .print-instruction { display: none; } 
        }
    </style>
</head>
<body>
    <div class="certificat-box">
        <div class="logo-container">
            <img src="logo.png" alt="Logo Together4Peace"> 
        </div>

        <div class="content-container">
            <h1>CERTIFICAT DE RÉUSSITE</h1>
            <p class="message">Ce certificat est décerné à:</p>

            <p class="nom-certifie"><?php echo htmlspecialchars($nom_utilisateur); ?></p>

            <h2>Pour avoir réussi le Quiz « <?php echo htmlspecialchars($titre_quiz); ?> »</h2>
            
            <p class="message">
                Avec un score remarquable de <?php echo htmlspecialchars($score_pourcentage); ?>%.
                <br><br>
                Ceci démontre une excellente compréhension des principes fondamentaux 
                de la Paix, de l'Inclusion et du dialogue constructif.
            </p>
            
            <div class="signature">
                <div>
                    <p>Date d'Obtention: <?php echo $date_obtention; ?></p>
                </div>
                <div>
                    <p>Signature de l'Organisation</p>
                </div>
            </div>

            <p class="print-instruction" style="margin-top: 50px; font-style: italic; color: #e74c3c;">
                Veuillez utiliser la fonction d'impression de votre navigateur (Ctrl+P ou Cmd+P) et choisir l'option "Enregistrer en PDF" pour télécharger votre certificat.
            </p>
        </div>
    </div>
</body>
</html>
<?php
$cert_html = ob_get_clean();

// affiche le certificat dans le navigateur
echo $cert_html;

// envoi email (uniquement si email valide)
if (!empty($email_utilisateur) && filter_var($email_utilisateur, FILTER_VALIDATE_EMAIL)) {
    $mail = new PHPMailer(true);
    try {
        // SMTP (laisser tel quel; change si tu utilises Mailtrap ou Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '';        
        $mail->Password   = '';     
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('no-reply@together4peace.tn', 'Together4Peace');
        $mail->addAddress($email_utilisateur, $nom_utilisateur);

        $mail->isHTML(true);
        $mail->Subject = 'Votre Certificat Together4Peace';
        $mail->Body    = $cert_html;

        $mail->send();
        echo "\n<!-- PHPMailer : mail envoyé avec succès à $email_utilisateur -->";
    } catch (Exception $e) {
        echo "\n<!-- PHPMailer ERREUR : " . $mail->ErrorInfo . " -->";
    }
} else {
    echo "\n<!-- Email invalide ou manquant : " . htmlspecialchars($email_utilisateur) . " -->";
}

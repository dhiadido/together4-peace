<?php
require_once __DIR__ . '/../../controller/QuizC.php'; 
require_once __DIR__ . '/../../controller/QuestionC.php';

// Vérifier que quiz_id vient bien en POST
if (!isset($_POST['quiz_id']) || empty($_POST['quiz_id'])) {
    header('Location: listQuizFront.php');
    exit();
}

// sanitize and get name + email from POST
$nom_utilisateur = !empty($_POST['nom']) ? trim($_POST['nom']) : "Utilisateur Together4Peace";
$nom_utilisateur = mb_substr(strip_tags($nom_utilisateur), 0, 80);

$email_utilisateur = !empty($_POST['email']) ? trim($_POST['email']) : "";
$email_utilisateur = filter_var($email_utilisateur, FILTER_SANITIZE_EMAIL);

$id_quiz = (int)$_POST['quiz_id'];
$questionC = new QuestionC();
$quizC = new QuizC();

$questionsDB = $questionC->recupererQuestionsParQuiz($id_quiz); 
$quiz = $quizC->recupererQuiz($id_quiz); 

$score = 0;
$totalQuestions = 0;
$resultatsDetails = [];

if ($questionsDB) {
    $totalQuestions = count($questionsDB);

    foreach ($questionsDB as $qDB) {
        $id_question = $qDB['id_question'];
        
        $reponse_utilisateur = $_POST["q_$id_question"] ?? null; 
        $reponse_correcte = $qDB['reponse_correcte']; 

        $estCorrect = false;

        if ($reponse_utilisateur !== null && $reponse_utilisateur == $reponse_correcte) {
            $score++;
            $estCorrect = true;
        }

        $choix_correct_key = 'choix' . $reponse_correcte;
        $choix_correct_texte = $qDB[$choix_correct_key] ?? '';

        $choix_utilisateur_texte = 'Non répondu';
        if ($reponse_utilisateur !== null) {
            $choix_utilisateur_key = 'choix' . $reponse_utilisateur;
            $choix_utilisateur_texte = $qDB[$choix_utilisateur_key] ?? 'Erreur de choix';
        }

        $resultatsDetails[] = [
            'texte' => $qDB['texte_question'],
            'reponse_utilisateur_texte' => $choix_utilisateur_texte,
            'reponse_correcte' => $reponse_correcte,
            'choix_correct' => $choix_correct_texte,
            'estCorrect' => $estCorrect
        ];
    }
}

$pourcentage = ($totalQuestions > 0) ? round(($score / $totalQuestions) * 100) : 0;
$messageResultat = "Votre score est de **$score** / $totalQuestions. ($pourcentage %)";
$couleur = '#e67e22'; 
$lien_certificat = null;
$certif_obtenu = false;

// tenter d'enregistrer resultat & certif via QuizC (existant dans ton code)
$certif_obtenu_db = false;
if (method_exists($quizC, 'enregistrerResultatEtCertif')) {
    try {
        // si ta méthode supporte email en 4eme param, on passe l'email, sinon adapte ta méthode
        $certif_obtenu_db = $quizC->enregistrerResultatEtCertif($nom_utilisateur, $id_quiz, $pourcentage, $email_utilisateur);
    } catch (Exception $e) {
        $certif_obtenu_db = false;
    }
}

// Optionnel: sauvegarde dans ScoreC si dispo
if (file_exists(__DIR__ . '/../../controller/ScoreC.php')) {
    require_once __DIR__ . '/../../controller/ScoreC.php';
    try {
        $scoreCtrl = new ScoreC();
        // NOTE: appeler avec 6 paramètres (id_quiz, nom, email, score, totalQuestions, pourcentage)
        // ceci évite erreur si ta ScoreC n'attend pas 'auto_submitted' en 7ème param.
        if (method_exists($scoreCtrl, 'ajouterScore')) {
            $scoreCtrl->ajouterScore($id_quiz, $nom_utilisateur, $email_utilisateur, $score, $totalQuestions, $pourcentage);
        }
    } catch (Exception $e) {
        error_log('Score save error: '.$e->getMessage());
    }
}

if ($pourcentage >= 80) {
    $couleur = '#2ecc71'; 
    $messageFelicitation = "Excellent travail ! Vous maîtrisez les concepts de Paix et d'Inclusion. **Félicitations pour votre certification !**";
    
    if ($certif_obtenu_db) {
        // Passer nom/email au générateur (GET) — on encode proprement
        $lien_certificat = "genererCertificat.php?quiz_id=" . $id_quiz .
                           "&score=" . $pourcentage .
                           "&user=" . urlencode($nom_utilisateur) .
                           "&quiz_titre=" . urlencode($quiz['titre']) .
                           "&email=" . urlencode($email_utilisateur);
        $certif_obtenu = true;
    }

} elseif ($pourcentage >= 50) {
    $couleur = '#f39c12'; 
    $messageFelicitation = "Bon résultat ! Continuez à vous informer pour renforcer vos connaissances.";
} else {
    $couleur = '#e74c3c'; 
    $messageFelicitation = "Vous pouvez faire mieux ! Profitez-en pour revoir les notions importantes.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultat du Quiz</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #ecf0f1; padding: 20px; }
        .result-container { max-width: 900px; margin: auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; text-align: center; }
        .score-box { text-align: center; padding: 30px; margin: 30px 0; border-radius: 15px; border: 3px solid <?php echo $couleur; ?>; background-color: <?php echo $couleur; ?>33; }
        .score-box h2 { color: <?php echo $couleur; ?>; margin-top: 0; font-size: 2em; }
        .score-box p { font-size: 1.2em; color: #555; }
        .feedback { text-align: center; font-size: 1.3em; margin-bottom: 40px; color: <?php echo $couleur; ?>; }

        .details { margin-top: 30px; }
        .question-detail { padding: 15px; border-radius: 8px; margin-bottom: 15px; }
        .question-detail.correct { background-color: #2ecc7140; }
        .question-detail.incorrect { background-color: #e74c3c30; }
        .detail-info { margin-top: 8px; font-size: 0.95em; }

        .btn-return {
            display: inline-block;
            margin-top: 25px;
            padding: 10px 20px;
            border-radius: 6px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-return:hover { background-color: #2980b9; }

        .btn-certif {
            display: inline-block;
            padding: 12px 24px;
            background-color: #e67e22;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1.1em;
            box-shadow: 0 4px #e67e22;
            transition: background-color 0.3s, transform 0.1s;
        }
        .btn-certif:hover {
            background-color: #f39c12;
            transform: translateY(1px);
            box-shadow: 0 3px #e67e22;
        }

        .extra-actions { text-align: center; margin-top: 18px; }
        .extra-actions a { display:inline-block; margin:6px; padding:10px 14px; border-radius:8px; background:#16a085; color:#fff; text-decoration:none; font-weight:bold; }
        .extra-actions a.export { background:#8e44ad; }
        .extra-actions a:hover { opacity:0.95; }
    </style>
</head>
<body>
    <div class="result-container">
        <h1>Résultat du Quiz : <strong><?php echo htmlspecialchars($quiz['titre']); ?></strong></h1>

        <div class="score-box">
            <h2><?php echo $pourcentage; ?> %</h2>
            <p><?php echo $messageResultat; ?></p>
        </div>
        
        <p class="feedback"><?php echo $messageFelicitation; ?></p>

        <?php if ($certif_obtenu && $lien_certificat) { ?>
        <div style="text-align: center; margin-bottom: 30px;">
            <a href="<?php echo $lien_certificat; ?>" target="_blank" class="btn-certif">
                🎁 Télécharger Votre Certificat "Together4Peace"
            </a>
            <p style="margin-top: 10px; font-size: 0.9em; color: #555;">
                (Le certificat s'ouvrira dans un nouvel onglet et pourra être envoyé par email)
            </p>
        </div>
        <?php } ?>

        <div class="extra-actions">
            <?php if (file_exists(__DIR__ . '/leaderboard.php') || file_exists(__DIR__ . '/../../view/front/leaderboard.php')): ?>
                <a href="leaderboard.php">🏆 Voir le classement</a>
            <?php endif; ?>
            <?php if (file_exists(__DIR__ . '/exportScores.php') || file_exists(__DIR__ . '/../../view/front/exportScores.php')): ?>
                <a href="exportScores.php" class="export">📥 Exporter CSV</a>
            <?php endif; ?>
        </div>

        <div class="details">
            <h2>Détails de vos Réponses</h2>
            <?php foreach ($resultatsDetails as $detail) { ?>
                <div class="question-detail <?php echo $detail['estCorrect'] ? 'correct' : 'incorrect'; ?>">
                    <p><strong>Question :</strong> <?php echo htmlspecialchars($detail['texte']); ?></p>
                    <div class="detail-info">
                        <strong>Votre Réponse :</strong> 
                        <?php echo htmlspecialchars($detail['reponse_utilisateur_texte']); ?>
                        <br>
                        <strong>Réponse Correcte :</strong> <?php echo htmlspecialchars($detail['choix_correct']); ?>
                        
                        <span style="float: right; font-weight: bold; color: <?php echo $detail['estCorrect'] ? '#2ecc71' : '#e74c3c'; ?>">
                            <?php echo $detail['estCorrect'] ? '✅ Correct' : '❌ Incorrect'; ?>
                        </span>
                    </div>
                </div>
            <?php } ?>
        </div>

        <a href="listQuizFront.php" class="btn-return">Retour à la liste des Quiz</a>
    </div>
</body>
</html>

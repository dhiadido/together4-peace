<?php
require_once __DIR__ . '/../../controller/QuizC.php'; 
require_once __DIR__ . '/../../controller/QuestionC.php'; 

// Vérification de l'ID du quiz
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: listQuizFront.php');
    exit();
}

$id_quiz = $_GET['id'];
$quizC = new QuizC();
$questionC = new QuestionC();

$quiz = $quizC->recupererQuiz($id_quiz); 
if (!$quiz) {
    die("Quiz introuvable.");
}

$questions = $questionC->recupererQuestionsParQuiz($id_quiz); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Quiz : <?php echo htmlspecialchars($quiz['titre']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f2f5; padding: 20px; }
        .quiz-container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); position: relative; }
        h1 { color: #2c3e50; text-align: center; border-bottom: 2px solid #ddd; padding-bottom: 15px; margin-bottom: 30px; }
        .quiz-info { text-align: center; color: #555; margin-bottom: 30px; font-style: italic; }
        fieldset { border: 1px solid #bdc3c7; border-radius: 8px; padding: 20px; margin-bottom: 25px; }
        legend { font-size: 1.2em; font-weight: bold; color: #3498db; padding: 0 10px; }
        label { display: block; margin: 10px 0; cursor: pointer; padding: 5px; border-radius: 4px; transition: background-color 0.2s; }
        label:hover { background-color: #ecf0f1; }
        input[type="radio"] { margin-right: 10px; }
        .submit-btn { width: 100%; padding: 15px; background-color: #e67e22; color: white; border: none; border-radius: 8px; font-size: 1.1em; cursor: pointer; transition: background-color 0.3s; }
        .submit-btn:hover { background-color: #d35400; }

        /* user name widget styles */
        .user-name-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #fff;
            border: 2px solid #e67e22;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .user-name-badge {
            margin-right: 12px;
            font-weight: bold;
            color: #2c3e50;
            font-size: 0.95em;
            display: inline-block;
            vertical-align: middle;
        }
        .name-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 9998;
        }
        .name-modal {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%,-50%);
            background: #fff;
            padding: 18px;
            border-radius: 8px;
            width: 320px;
            z-index: 9999;
            box-shadow: 0 12px 36px rgba(0,0,0,0.18);
        }
        .name-modal h3 { margin: 0 0 8px 0; font-size: 1.05em; color:#2c3e50; }
        .name-modal input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccd1d9;
            border-radius: 6px;
            font-size: 1em;
        }
        .name-modal .actions { margin-top: 12px; text-align: right; }
        .name-modal .btn {
            padding: 8px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }
        .btn-save { background: #2ecc71; color: white; }
        .btn-cancel { background: #bdc3c7; color: #222; margin-right: 8px; }
        @media (max-width: 600px) {
            .user-name-badge { display: none; }
            .quiz-container { padding: 18px; }
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <h1>Commencer le Quiz : <?php echo htmlspecialchars($quiz['titre']); ?></h1>
        <div class="quiz-info">
            <p>Ce quiz comporte <strong><?php echo count($questions); ?></strong> questions.</p>
        </div>

        <!-- User name widget (icon + modal) -->
        <div style="display:flex; justify-content:flex-end; align-items:center; margin-bottom:10px;">
            <div class="user-name-badge" id="userNameDisplay" style="display:none;"></div>
            <button type="button" class="user-name-btn" id="openNameBtn" title="Entrer votre nom pour le certificat" aria-label="Entrer votre nom pour le certificat">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="#e67e22" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M20 22C20 17.5817 16.4183 14 12 14C7.58172 14 4 17.5817 4 22" stroke="#e67e22" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        <div class="name-modal-backdrop" id="nameBackdrop"></div>
        <div class="name-modal" id="nameModal" role="dialog" aria-modal="true" aria-labelledby="nameModalTitle">
            <h3 id="nameModalTitle">Indique ton nom & prénom pour le certificat</h3>
            <input type="text" id="nameField" placeholder="Ex : Ali Ben Ali" maxlength="80" autocomplete="name">
            <div class="actions" style="margin-top:10px; text-align:right;">
                <button type="button" class="btn btn-cancel" id="cancelName">Annuler</button>
                <button type="button" class="btn btn-save" id="saveName">Sauvegarder</button>
            </div>
        </div>
        <!-- End widget -->

        <form action="resultatQuiz.php" method="POST" id="quizForm" novalidate>
            <input type="hidden" name="quiz_id" value="<?php echo $id_quiz; ?>">
            <!-- hidden input for name (will be filled by widget) -->
            <input type="hidden" id="nomInputForm" name="nom" value="">

            <?php $compteur = 1; foreach ($questions as $q) { ?>
                <fieldset>
                    <legend>Question <?php echo $compteur++; ?> :</legend>
                    <p><strong><?php echo htmlspecialchars($q['texte_question']); ?></strong></p>
                    
                    <label>
                        <input type="radio" name="q_<?php echo $q['id_question']; ?>" value="1"> 
                        <?php echo htmlspecialchars($q['choix1']); ?>
                    </label>
                    
                    <?php if (!empty($q['choix2'])) { ?>
                        <label>
                            <input type="radio" name="q_<?php echo $q['id_question']; ?>" value="2"> 
                            <?php echo htmlspecialchars($q['choix2']); ?>
                        </label>
                    <?php } ?>
                    
                    <?php if (!empty($q['choix3'])) { ?>
                        <label>
                            <input type="radio" name="q_<?php echo $q['id_question']; ?>" value="3"> 
                            <?php echo htmlspecialchars($q['choix3']); ?>
                        </label>
                    <?php } ?>
                </fieldset>
            <?php } ?>
            
            <button type="submit" class="submit-btn">Terminer le Quiz et voir mon Score</button>
        </form>
    </div>

<script>
// JS widget : modal + store in sessionStorage + put in hidden input inside form
(function(){
    const openBtn = document.getElementById('openNameBtn');
    const modal = document.getElementById('nameModal');
    const backdrop = document.getElementById('nameBackdrop');
    const saveBtn = document.getElementById('saveName');
    const cancelBtn = document.getElementById('cancelName');
    const nameField = document.getElementById('nameField');
    const nomInputForm = document.getElementById('nomInputForm');
    const display = document.getElementById('userNameDisplay');

    // preload from sessionStorage if exist
    const stored = sessionStorage.getItem('t4p_user_name') || '';
    if (stored) {
        nomInputForm.value = stored;
        display.textContent = stored;
        display.style.display = 'inline-block';
    }

    function openModal(){
        nameField.value = nomInputForm.value || '';
        backdrop.style.display = 'block';
        modal.style.display = 'block';
        nameField.focus();
    }
    function closeModal(){
        backdrop.style.display = 'none';
        modal.style.display = 'none';
    }

    openBtn.addEventListener('click', openModal);
    backdrop.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    saveBtn.addEventListener('click', function(){
        const v = nameField.value.trim();
        if (!v) {
            alert('Le nom ne peut pas être vide.');
            nameField.focus();
            return;
        }
        // set hidden input in form + sessionStorage + display badge
        nomInputForm.value = v;
        sessionStorage.setItem('t4p_user_name', v);
        display.textContent = v;
        display.style.display = 'inline-block';
        closeModal();
    });

    nameField.addEventListener('keydown', function(e){
        if (e.key === 'Enter') {
            e.preventDefault();
            saveBtn.click();
        }
    });

    // Ensure hidden input exists in the form before submit (already inside)
    document.getElementById('quizForm').addEventListener('submit', function(){
        // If no name entered, we allow submission but set default
        if (!nomInputForm.value) {
            nomInputForm.value = 'Utilisateur Together4Peace';
        }
    });

})();
</script>

</body>
</html>

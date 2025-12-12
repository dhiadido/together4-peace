<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Together4Peace</title>
    <link rel="stylesheet" href="../../assets/css/peacebot.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo-link">
            <div class="logo">
                <img src="..\..\assets\images\logo.png" alt="Logo Together4Peace" class="header-logo">
                <span class="site-name">Together4Peace</span>
            </div>
        </a>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="about.html">À Propos</a></li>
                <li><a href="quiz.php">Quiz</a></li>
                <li><a href="testimonials.html">Témoignages</a></li>
                <li><a href="charter.html">Charte</a></li>
                <li><a href="..\Backoffice\index.php">Backoffice</a></li>
            </ul>
        </nav>
        <a href="#admin-login" class="btn btn-donate">Espace Admin</a>
    </header>

    <button id="peacebot-button" onclick="togglePeaceBot()">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
        </svg>
    </button>

    <div id="peacebot-container">
        <div class="peacebot-header">
            <div class="peacebot-header-content">
                <div class="peacebot-avatar">🤖</div>
                <div class="peacebot-title">
                    <h3>PeaceBot</h3>
                    <p>Votre Assistant de Paix</p>
                </div>
            </div>
            <button class="peacebot-close" onclick="togglePeaceBot()">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="peacebot-messages" id="peacebot-messages">
            <div class="message bot">
                <div class="message-avatar bot">🤖</div>
                <div class="message-bubble">
                    Bonjour! Je suis PeaceBot, votre assistant de paix intelligent. Je suis là pour :
                    <br><br>
                    ✨ Répondre à vos questions sur la paix et l'inclusion<br>
                    💡 Expliquer des concepts et défis quotidiens<br>
                    🌟 Vous motiver et inspirer chaque jour
                    <br><br>
                    Comment puis-je vous aider aujourd'hui?
                </div>
            </div>

            <div class="quick-actions" id="peacebot-quick-actions">
                <button class="quick-action" onclick="sendQuickPeaceMessage('Qu\'est-ce que la paix?')">Qu'est-ce que la paix?</button>
                <button class="quick-action" onclick="sendQuickPeaceMessage('Comment puis-je contribuer?')">Comment contribuer?</button>
                <button class="quick-action" onclick="sendQuickPeaceMessage('Inspire-moi aujourd\'hui')">Inspire-moi</button>
            </div>

            <div class="message bot">
                <div class="message-avatar bot">🤖</div>
                <div class="typing-indicator" id="peacebot-typing">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="peacebot-input">
            <input type="text" id="peacebot-input" placeholder="Écrivez votre message..." onkeypress="handlePeaceBotKeyPress(event)">
            <button id="peacebot-send" onclick="sendPeaceBotMessage()">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
            </button>
        </div>
    </div>

    <script src="../../assets/js/peacebot.js"></script>
</body>
</html>
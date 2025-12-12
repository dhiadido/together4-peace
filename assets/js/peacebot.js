/**
 * PeaceBot HYBRIDE - JavaScript
 * Copiez ce fichier dans : assets/js/peacebot.js
 */

let peaceBotHistory = [];
let isProcessing = false;

/**
 * Initialisation
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ PeaceBot HYBRIDE 3.5 initialisé !');
    addWelcomeMessage();
});

/**
 * Message de bienvenue
 */
function addWelcomeMessage() {
    const welcomeMsg = `Bonjour ! 👋 Je suis **PeaceBot HYBRIDE** !

Je combine :
🧠 Une base de connaissances experte
🤖 L'IA Mistral-7B pour plus de flexibilité

Posez-moi vos questions sur la **paix**, l'**inclusion** et la **société** ! 💙`;
    
    addPeaceBotMessage(welcomeMsg, 'bot');
}

/**
 * Toggle chatbot
 */
function togglePeaceBot() {
    const container = document.getElementById('peacebot-container');
    const input = document.getElementById('peacebot-input');
    
    container.classList.toggle('open');
    
    if (container.classList.contains('open')) {
        setTimeout(() => input && input.focus(), 300);
    }
}

/**
 * Handle Enter key
 */
function handlePeaceBotKeyPress(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendPeaceBotMessage();
    }
}

/**
 * Quick messages
 */
function sendQuickPeaceMessage(message) {
    const input = document.getElementById('peacebot-input');
    if (input) {
        input.value = message;
        sendPeaceBotMessage();
    }
}

/**
 * Envoyer message
 */
async function sendPeaceBotMessage() {
    if (isProcessing) return;
    
    const input = document.getElementById('peacebot-input');
    const message = input.value.trim();
    
    if (!message) {
        showNotification('⚠️ Veuillez entrer un message');
        return;
    }
    
    if (message.length > 500) {
        showNotification('⚠️ Message trop long (max 500 caractères)');
        return;
    }
    
    // Cacher quick actions
    hideQuickActions();
    
    // Ajouter message utilisateur
    addPeaceBotMessage(message, 'user');
    input.value = '';
    
    // Loading
    isProcessing = true;
    setPeaceBotLoading(true);
    
    try {
        const response = await fetch('../../Controller/peacebot.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                message: message,
                history: peaceBotHistory
            })
        });
        
        const data = await response.json();
        
        if (data.success && data.response) {
            // Mise à jour historique
            peaceBotHistory = data.history;
            
            // Ajouter réponse bot avec badge de source
            let botMessage = data.response;
            
            // Ajouter badge de source en bas du message
            if (data.source === 'knowledge_base') {
                botMessage += '\n\n💡 *Base de connaissances*';
            } else if (data.source === 'ai') {
                botMessage += '\n\n🤖 *IA Mistral-7B*';
            }
            
            addPeaceBotMessage(botMessage, 'bot');
            
            // Log pour debug
            console.log(`✅ Réponse de: ${data.source}`);
        } else {
            throw new Error(data.error || 'Erreur inconnue');
        }
    } catch (error) {
        console.error('❌ Erreur PeaceBot:', error);
        
        let errorMsg = "❌ Une erreur est survenue.";
        
        if (error.message.includes('Failed to fetch')) {
            errorMsg = "🔌 Erreur de connexion. Vérifiez que le serveur est démarré.";
        } else if (error.message.includes('Rate limit')) {
            errorMsg = "⏰ Trop de requêtes. Patientez une minute.";
        }
        
        addPeaceBotMessage(errorMsg, 'bot');
    } finally {
        isProcessing = false;
        setPeaceBotLoading(false);
    }
}

/**
 * Ajouter message à l'UI
 */
function addPeaceBotMessage(text, sender) {
    const container = document.getElementById('peacebot-messages');
    if (!container) return;
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${sender}`;
    
    // Avatar
    const avatar = document.createElement('div');
    avatar.className = `message-avatar ${sender}`;
    avatar.textContent = sender === 'bot' ? '🤖' : '👤';
    
    // Bubble
    const bubble = document.createElement('div');
    bubble.className = 'message-bubble';
    bubble.innerHTML = formatMessage(text);
    
    messageDiv.appendChild(avatar);
    messageDiv.appendChild(bubble);
    
    // Insérer avant typing indicator
    const typingIndicator = container.querySelector('.typing-indicator');
    if (typingIndicator) {
        container.insertBefore(messageDiv, typingIndicator.parentElement);
    } else {
        container.appendChild(messageDiv);
    }
    
    // Scroll
    setTimeout(() => {
        container.scrollTop = container.scrollHeight;
    }, 100);
}

/**
 * Formatter le message
 */
function formatMessage(text) {
    // Line breaks
    text = text.replace(/\n/g, '<br>');
    
    // URLs
    text = text.replace(
        /(https?:\/\/[^\s]+)/g,
        '<a href="$1" target="_blank" rel="noopener">$1</a>'
    );
    
    // Bold **text**
    text = text.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
    
    // Italic *text*
    text = text.replace(/\*([^*]+)\*/g, '<em>$1</em>');
    
    return text;
}

/**
 * Loading state
 */
function setPeaceBotLoading(isLoading) {
    const input = document.getElementById('peacebot-input');
    const button = document.getElementById('peacebot-send');
    const typing = document.getElementById('peacebot-typing');
    
    if (input) input.disabled = isLoading;
    if (button) button.disabled = isLoading;
    if (typing) typing.classList.toggle('active', isLoading);
}

/**
 * Cacher quick actions
 */
function hideQuickActions() {
    const quickActions = document.getElementById('peacebot-quick-actions');
    if (quickActions) {
        quickActions.style.display = 'none';
    }
}

/**
 * Notification
 */
function showNotification(message) {
    console.log(`[NOTIFICATION] ${message}`);
    // Vous pouvez ajouter un toast ici
}

/**
 * Effacer historique
 */
function clearPeaceBotHistory() {
    if (confirm('Voulez-vous effacer l\'historique ?')) {
        peaceBotHistory = [];
        
        const container = document.getElementById('peacebot-messages');
        if (container) {
            container.innerHTML = `
                <div class="message bot">
                    <div class="message-avatar bot">🤖</div>
                    <div class="typing-indicator" id="peacebot-typing">
                        <div class="typing-dots">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                </div>
            `;
        }
        
        addWelcomeMessage();
        console.log('✅ Historique effacé');
    }
}

/**
 * Debug tools
 */
window.PeaceBotDebug = {
    getHistory: () => peaceBotHistory,
    clearHistory: clearPeaceBotHistory,
    isProcessing: () => isProcessing,
    version: '3.5 Hybrid'
};
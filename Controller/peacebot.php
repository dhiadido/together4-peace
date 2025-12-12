<?php
/**
 * PeaceBot HYBRIDE - Version 3.5
 * Base de connaissances + HuggingFace Mistral-7B
 * Copiez ce fichier dans : Controller/peacebot.php
 */

// =====================================================
// CONFIGURATION INLINE (pas besoin de config.php)
// =====================================================
define('HUGGINGFACE_API_TOKEN', getenv('HF_TOKEN'));  // ⚠️ REMPLACEZ ICI
define('HUGGINGFACE_MODEL', 'mistralai/Mistral-7B-Instruct-v0.2');
define('HYBRID_MODE', true);
define('MAX_MESSAGES_PER_MINUTE', 15);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Rate limiting
session_start();
if (!checkRateLimit()) {
    http_response_code(429);
    echo json_encode([
        'error' => 'Trop de requêtes',
        'message' => 'Veuillez patienter une minute avant de réessayer.'
    ]);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['message']) || empty(trim($data['message']))) {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required']);
    exit;
}

$userMessage = trim($data['message']);
$conversationHistory = isset($data['history']) ? $data['history'] : [];

// Ajouter message utilisateur
$conversationHistory[] = [
    'role' => 'user',
    'content' => $userMessage
];

// SYSTÈME HYBRIDE : Base de connaissances d'abord, puis IA
$botResponse = getHybridResponse($userMessage, $conversationHistory);

// Ajouter réponse bot
$conversationHistory[] = [
    'role' => 'assistant',
    'content' => $botResponse['response']
];

echo json_encode([
    'success' => true,
    'response' => $botResponse['response'],
    'source' => $botResponse['source'], // 'knowledge_base' ou 'ai'
    'history' => $conversationHistory
]);

/**
 * SYSTÈME HYBRIDE INTELLIGENT
 */
function getHybridResponse($userMessage, $history) {
    // ÉTAPE 1 : Chercher dans la base de connaissances
    $kbResponse = searchKnowledgeBase($userMessage);
    
    if ($kbResponse !== null) {
        return [
            'response' => $kbResponse,
            'source' => 'knowledge_base'
        ];
    }
    
    // ÉTAPE 2 : Si pas trouvé, utiliser l'IA Mistral-7B
    if (HYBRID_MODE && HUGGINGFACE_API_TOKEN !== 'YOUR_TOKEN_HERE') {
        $aiResponse = getAIResponse($userMessage, $history);
        return [
            'response' => $aiResponse,
            'source' => 'ai'
        ];
    }
    
    // ÉTAPE 3 : Fallback si pas d'IA configurée
    return [
        'response' => getDefaultResponse($userMessage),
        'source' => 'fallback'
    ];
}

/**
 * BASE DE CONNAISSANCES COMPLÈTE
 */
function searchKnowledgeBase($message) {
    $kb = getKnowledgeBase();
    $message = strtolower($message);
    
    // PAIX
    if (preg_match('/\b(paix|peace|pacifique)\b/i', $message)) {
        if (preg_match('/\b(construire|créer|promouvoir|faire)\b/i', $message)) {
            return $kb['paix']['construire'];
        }
        if (preg_match('/\b(citation|phrase)\b/i', $message)) {
            return $kb['paix']['citation'];
        }
        if (preg_match('/\b(obstacle|problème|défi)\b/i', $message)) {
            return $kb['paix']['obstacles'];
        }
        if (preg_match('/\b(type|forme)\b/i', $message)) {
            return $kb['paix']['types'];
        }
        return $kb['paix']['definition'];
    }
    
    // INCLUSION
    if (preg_match('/\b(inclusion|inclusif|inclusive)\b/i', $message)) {
        if (preg_match('/\b(important|pourquoi|essentiel)\b/i', $message)) {
            return $kb['inclusion']['importance'];
        }
        if (preg_match('/\b(pratiquer|faire|comment)\b/i', $message)) {
            return $kb['inclusion']['pratiques'];
        }
        if (preg_match('/\b(diversité|différence)\b/i', $message)) {
            return $kb['inclusion']['vs_diversite'];
        }
        return $kb['inclusion']['definition'];
    }
    
    // SOCIÉTÉ
    if (preg_match('/\b(société|social|communauté)\b/i', $message)) {
        if (preg_match('/\b(cohésion|unité)\b/i', $message)) {
            return $kb['societe']['cohesion'];
        }
        if (preg_match('/\b(défi|problème|enjeu)\b/i', $message)) {
            return $kb['societe']['defis'];
        }
        return $kb['societe']['definition'];
    }
    
    // TOGETHER4PEACE
    if (preg_match('/\b(together|t4p|together4peace)\b/i', $message)) {
        if (preg_match('/\b(mission|but|objectif)\b/i', $message)) {
            return $kb['together4peace']['mission'];
        }
        if (preg_match('/\b(valeur|principe)\b/i', $message)) {
            return $kb['together4peace']['valeurs'];
        }
        if (preg_match('/\b(contribuer|aider|participer)\b/i', $message)) {
            return $kb['together4peace']['contribution'];
        }
        return $kb['together4peace']['mission'];
    }
    
    // DISCRIMINATION
    if (preg_match('/\b(discrimin|racisme|sexisme|homophob)\b/i', $message)) {
        if (preg_match('/\b(lutter|combattre)\b/i', $message)) {
            return $kb['discrimination']['lutte'];
        }
        return $kb['discrimination']['definition'];
    }
    
    // DIALOGUE
    if (preg_match('/\b(dialogue|conversation|discussion)\b/i', $message)) {
        if (preg_match('/\b(technique|méthode|comment)\b/i', $message)) {
            return $kb['dialogue']['techniques'];
        }
        return $kb['dialogue']['importance'];
    }
    
    // INSPIRATION
    if (preg_match('/\b(inspir|motiv|encourag)\b/i', $message)) {
        if (preg_match('/\b(qui|personne|leader)\b/i', $message)) {
            return $kb['inspiration']['leaders'];
        }
        return $kb['inspiration']['actions'];
    }
    
    // AIDE / CONTRIBUTION
    if (preg_match('/\b(aide|aider|contribu|particip)\b/i', $message)) {
        return $kb['together4peace']['contribution'];
    }
    
    // SALUTATIONS
    if (preg_match('/\b(bonjour|salut|hello|hey)\b/i', $message)) {
        return "Bonjour ! 👋 Bienvenue sur PeaceBot !

Je suis votre assistant intelligent spécialisé en **paix**, **inclusion** et **société**. Je combine une base de connaissances experte avec l'intelligence artificielle pour vous offrir les meilleures réponses ! 

Posez-moi vos questions ! 💙

**Suggestions :**
• C'est quoi la paix ?
• Comment pratiquer l'inclusion ?
• Quelle est votre mission ?
• Inspirez-moi !";
    }
    
    if (preg_match('/\b(merci|thanks)\b/i', $message)) {
        return "Avec grand plaisir ! 😊 C'est un honneur de vous accompagner dans votre engagement pour la paix et l'inclusion.

N'hésitez pas à revenir, je suis toujours là pour vous aider ! 🌍✨";
    }
    
    // Pas de correspondance trouvée
    return null;
}

/**
 * RÉPONSE IA AVEC MISTRAL-7B
 */
function getAIResponse($userMessage, $history) {
    $context = buildContext($history);
    
    $systemPrompt = "[INST] Tu es PeaceBot, un assistant expert de Together4Peace spécialisé en paix, inclusion et société.

RÈGLES STRICTES:
- Réponds TOUJOURS en français
- Sois chaleureux, inspirant et bienveillant
- Maximum 3-4 phrases
- Focus sur: paix, inclusion, société, discrimination, dialogue
- Utilise 1-2 émojis maximum
- Si hors sujet, redirige gentiment vers tes domaines d'expertise [/INST]";

    $fullPrompt = $systemPrompt . "\n\n" . $context . "Utilisateur: " . $userMessage . "\n\nPeaceBot:";
    
    $apiUrl = "https://api-inference.huggingface.co/models/" . HUGGINGFACE_MODEL;
    
    $requestData = [
        'inputs' => $fullPrompt,
        'parameters' => [
            'max_new_tokens' => 250,
            'temperature' => 0.7,
            'top_p' => 0.9,
            'do_sample' => true,
            'return_full_text' => false,
            'repetition_penalty' => 1.1
        ]
    ];
    
    $ch = curl_init($apiUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($requestData),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . HUGGINGFACE_API_TOKEN
        ],
        CURLOPT_TIMEOUT => 30
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if (isset($data[0]['generated_text'])) {
            return cleanAIResponse($data[0]['generated_text']);
        }
    } elseif ($httpCode === 503) {
        return "⏳ Le modèle IA démarre (première utilisation). Patientez 20 secondes et réessayez ! Le modèle restera ensuite actif. 🚀";
    }
    
    return getDefaultResponse($userMessage);
}

/**
 * CONSTRUIRE CONTEXTE DE CONVERSATION
 */
function buildContext($history) {
    $context = '';
    $recent = array_slice($history, -4);
    
    foreach ($recent as $msg) {
        if ($msg['role'] === 'user') {
            $context .= "Utilisateur: " . $msg['content'] . "\n";
        } else {
            $context .= "PeaceBot: " . $msg['content'] . "\n";
        }
    }
    
    return $context;
}

/**
 * NETTOYER RÉPONSE IA
 */
function cleanAIResponse($text) {
    $text = preg_replace('/\[INST\].*?\[\/INST\]/s', '', $text);
    $text = preg_replace('/Utilisateur:.*$/s', '', $text);
    $text = preg_replace('/PeaceBot:.*$/s', '', $text);
    $text = trim($text);
    
    if (strlen($text) > 500) {
        $text = substr($text, 0, 500);
        $lastPeriod = strrpos($text, '.');
        if ($lastPeriod) {
            $text = substr($text, 0, $lastPeriod + 1);
        }
    }
    
    return $text;
}

/**
 * RÉPONSE PAR DÉFAUT
 */
function getDefaultResponse($message) {
    return "Je suis PeaceBot 🤖, spécialisé dans les thèmes de la **paix**, l'**inclusion** et la **société**.

Posez-moi vos questions sur :
🕊️ La paix et la non-violence
🤝 L'inclusion et la diversité  
🌍 La société et la cohésion sociale
✨ Together4Peace et nos actions

Comment puis-je vous aider ? 💙";
}

/**
 * RATE LIMITING
 */
function checkRateLimit() {
    if (!isset($_SESSION['requests'])) {
        $_SESSION['requests'] = [];
    }
    
    $now = time();
    $_SESSION['requests'] = array_filter($_SESSION['requests'], function($t) use ($now) {
        return ($now - $t) < 60;
    });
    
    if (count($_SESSION['requests']) >= MAX_MESSAGES_PER_MINUTE) {
        return false;
    }
    
    $_SESSION['requests'][] = $now;
    return true;
}

/**
 * BASE DE CONNAISSANCES
 */
function getKnowledgeBase() {
    return [
        'paix' => [
            'definition' => "La paix est bien plus que l'absence de guerre. C'est un état d'harmonie, de justice sociale et de respect mutuel entre les individus et les communautés. La paix se construit au quotidien à travers nos actions, nos paroles et notre engagement envers autrui. 🕊️",
            
            'types' => "Il existe deux types de paix :

**Paix négative** : L'absence de violence directe et de conflit armé.

**Paix positive** : La présence de justice sociale, d'égalité, de respect des droits humains et de bien-être collectif. Together4Peace œuvre pour une paix positive ! ✨",
            
            'construire' => "Pour construire la paix, vous pouvez :

✓ Pratiquer l'écoute active et l'empathie
✓ Promouvoir le dialogue interculturel
✓ Lutter contre les discriminations
✓ Éduquer à la non-violence
✓ Participer à des initiatives communautaires
✓ Défendre les droits humains
✓ Cultiver la tolérance et le respect

Chaque petit geste compte ! 🤝",

            'citation' => "\"La paix commence par un sourire\" - Mère Teresa

\"Soyez le changement que vous voulez voir dans le monde\" - Gandhi

\"La paix n'est pas l'absence de conflit, c'est la capacité de gérer les conflits par des moyens pacifiques\" - Ronald Reagan 🌟",
            
            'obstacles' => "Les principaux obstacles à la paix sont :

• Les inégalités sociales et économiques
• Les discriminations et les préjugés
• Le manque d'éducation et de dialogue
• La violence structurelle
• L'injustice et la corruption
• L'intolérance religieuse ou culturelle

Together4Peace travaille à surmonter ces obstacles ! 💪"
        ],

        'inclusion' => [
            'definition' => "L'inclusion est la pratique qui consiste à garantir que chaque personne, quelles que soient ses différences (origine, genre, orientation sexuelle, handicap, religion, âge), se sente valorisée, respectée et ait un accès équitable aux opportunités. 🌈",
            
            'importance' => "L'inclusion est essentielle car elle :

✓ Enrichit notre société par la diversité
✓ Favorise l'innovation et la créativité
✓ Réduit les discriminations et les inégalités
✓ Renforce la cohésion sociale
✓ Permet à chacun de réaliser son potentiel
✓ Crée des communautés plus justes et harmonieuses

Une société inclusive est une société forte ! 💪",

            'pratiques' => "Pour pratiquer l'inclusion au quotidien :

1. **Écoutez** les voix marginalisées
2. **Remettez en question** vos propres préjugés
3. **Utilisez** un langage inclusif
4. **Créez** des espaces accueillants pour tous
5. **Défendez** les personnes discriminées
6. **Éduquez-vous** sur les différentes cultures
7. **Agissez** contre les discriminations

Ensemble, créons un monde inclusif ! 🤝",

            'vs_diversite' => "**Diversité** = La présence de différences (origine, genre, âge, etc.)

**Inclusion** = Créer un environnement où ces différences sont valorisées et où chacun se sent appartenir

La diversité, c'est inviter à la fête. L'inclusion, c'est inviter à danser ! 💃🕺"
        ],

        'societe' => [
            'definition' => "La société est l'ensemble des individus vivant en communauté, partageant des règles, des valeurs et des institutions communes. Une société saine est fondée sur la justice, l'égalité et le respect mutuel. 🏛️",
            
            'cohesion' => "La cohésion sociale repose sur :

✓ Des valeurs partagées (respect, solidarité, justice)
✓ Le dialogue interculturel et intergénérationnel
✓ La réduction des inégalités
✓ L'accès équitable aux ressources
✓ La participation citoyenne active
✓ La confiance dans les institutions

Together4Peace renforce la cohésion sociale ! 🤝",

            'defis' => "Les défis sociétaux actuels :

• **Inégalités** croissantes (richesse, opportunités)
• **Polarisation** politique et sociale
• **Discrimination** systémique
• **Crise climatique** et ses impacts sociaux
• **Migration** et intégration
• **Radicalisation** et extrémisme
• **Fracture numérique**

Ensemble, nous pouvons relever ces défis ! 💪"
        ],

        'together4peace' => [
            'mission' => "Together4Peace est une organisation dédiée à la promotion de la paix, de l'inclusion et de l'inspiration. Notre mission est de créer un monde où chaque personne peut s'épanouir dans le respect, la dignité et l'harmonie. 🌍

Nous agissons à travers l'éducation, le dialogue interculturel et des initiatives concrètes pour construire une société plus juste et pacifique.",

            'valeurs' => "Nos valeurs fondamentales :

🕊️ **Paix** : Promouvoir la non-violence et le dialogue
🤝 **Inclusion** : Valoriser la diversité et l'égalité
✨ **Inspiration** : Motiver le changement positif
💙 **Empathie** : Comprendre et respecter autrui
🌟 **Justice** : Défendre l'équité et les droits humains
🌈 **Respect** : Honorer toutes les différences",

            'contribution' => "Comment contribuer à Together4Peace :

1. **Signez notre charte** de paix et d'inclusion
2. **Participez** à nos événements et ateliers
3. **Partagez** notre mission sur les réseaux sociaux
4. **Bénévolez** pour nos projets communautaires
5. **Faites un don** pour soutenir nos programmes
6. **Proposez** vos idées et initiatives
7. **Devenez ambassadeur** de la paix dans votre communauté

Chaque action compte ! 💪"
        ],

        'discrimination' => [
            'definition' => "La discrimination est le traitement injuste ou inégal d'une personne ou d'un groupe en raison de caractéristiques comme l'origine, le genre, l'orientation sexuelle, l'âge, le handicap ou la religion. C'est un obstacle majeur à la paix et à l'inclusion. ⚖️",

            'lutte' => "Pour lutter contre la discrimination :

✓ **Éduquez-vous** sur les biais inconscients
✓ **Intervenez** quand vous êtes témoin de discrimination
✓ **Soutenez** les personnes discriminées
✓ **Remettez en question** les stéréotypes
✓ **Utilisez** un langage respectueux
✓ **Signalez** les actes discriminatoires
✓ **Promouvez** la diversité et l'égalité

Soyez un allié actif ! 🤝"
        ],

        'dialogue' => [
            'importance' => "Le dialogue est l'outil le plus puissant pour construire la paix et l'inclusion. Il permet de :

✓ Comprendre les perspectives différentes
✓ Résoudre les conflits pacifiquement
✓ Construire des ponts entre les communautés
✓ Déconstruire les préjugés
✓ Créer de l'empathie et de la connexion
✓ Trouver des solutions communes

Le dialogue transforme les ennemis en partenaires ! 🗣️",

            'techniques' => "Techniques de dialogue constructif :

1. **Écoute active** : Écoutez pour comprendre, pas pour répondre
2. **Questions ouvertes** : Encouragez l'expression profonde
3. **Empathie** : Mettez-vous à la place de l'autre
4. **Non-jugement** : Suspendez vos préjugés
5. **Respect** : Valorisez toutes les opinions
6. **Patience** : Donnez du temps au processus
7. **Authenticité** : Soyez sincère et vulnérable

Le dialogue change le monde ! 💬"
        ],

        'inspiration' => [
            'leaders' => "Figures inspirantes de la paix :

🕊️ **Nelson Mandela** - Réconciliation et pardon
🕊️ **Martin Luther King Jr.** - Justice et droits civiques
🕊️ **Malala Yousafzai** - Éducation et droits des filles
🕊️ **Gandhi** - Non-violence et résistance pacifique
🕊️ **Mère Teresa** - Compassion et service
🕊️ **Desmond Tutu** - Vérité et réconciliation

Vous aussi, vous pouvez être une source d'inspiration ! ✨",

            'actions' => "Actions inspirantes au quotidien :

• Sourire et saluer un étranger
• Défendre quelqu'un qui est intimidé
• Partager un message positif
• Aider un voisin dans le besoin
• Écouter sans juger
• Pardonner et lâcher prise
• Célébrer les différences

Soyez le changement ! 🌟"
        ]
    ];
}
?>
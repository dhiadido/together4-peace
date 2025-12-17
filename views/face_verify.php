<?php
session_start();
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Face ID - Together4Peace</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .auth-page {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 40px;
            width: 100%;
            max-width: 600px;
        }
        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .auth-header h2 {
            color: var(--color-primary);
            margin-bottom: 10px;
        }
        #video {
            width: 100%;
            max-width: 500px;
            border-radius: 10px;
            margin: 20px auto;
            display: block;
            background: #000;
        }
        #canvas {
            display: none;
        }
        .face-detection-area {
            position: relative;
            margin: 20px 0;
            text-align: center;
        }
        .btn-face {
            background-color: var(--color-primary);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            margin: 10px 5px;
            transition: all 0.3s;
            width: 100%;
            max-width: 300px;
        }
        .btn-face:hover {
            background-color: #001a3d;
        }
        .btn-face:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .status-message {
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
            text-align: center;
            font-weight: 500;
        }
        .status-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .status-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .button-group {
            text-align: center;
            margin-top: 20px;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: var(--color-primary);
            text-decoration: none;
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <img src="../logo.png" alt="Logo Together4Peace" class="auth-logo" style="height: 60px; margin-bottom: 20px;">
            <h2>🔐 Connexion Face ID</h2>
            <p>Scannez votre visage pour vous connecter</p>
        </div>

        <?php if ($error): ?>
            <div class="status-message status-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="face-detection-area">
            <video id="video" autoplay playsinline></video>
            <canvas id="canvas"></canvas>
            <div id="statusMessage" class="status-message" style="display: none;"></div>
        </div>

        <div class="button-group">
            <button id="startCamera" class="btn-face">
                <i class="fas fa-video"></i> Activer la caméra
            </button>
            <button id="verifyFace" class="btn-face" disabled>
                <i class="fas fa-user-check"></i> Vérifier mon visage
            </button>
        </div>

        <div class="back-link">
            <a href="login.php"><i class="fas fa-arrow-left"></i> Retour à la connexion classique</a>
        </div>
    </div>

    <script src="face-api.min.js"></script>
    <script>
        let stream = null;
        let modelsLoaded = false;
        let isVerifying = false;

        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const startCameraBtn = document.getElementById('startCamera');
        const verifyFaceBtn = document.getElementById('verifyFace');
        const statusMessage = document.getElementById('statusMessage');

        // Charger les modèles face-api
        async function loadModels() {
            try {
                statusMessage.textContent = 'Chargement des modèles de reconnaissance faciale...';
                statusMessage.className = 'status-message status-info';
                statusMessage.style.display = 'block';

                const MODEL_URL = './models/';
                
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                    faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
                ]);

                modelsLoaded = true;
                statusMessage.textContent = 'Modèles chargés avec succès !';
                statusMessage.className = 'status-message status-success';
                setTimeout(() => {
                    statusMessage.style.display = 'none';
                }, 2000);
            } catch (error) {
                console.error('Erreur lors du chargement des modèles:', error);
                statusMessage.textContent = 'Erreur lors du chargement des modèles. Veuillez rafraîchir la page.';
                statusMessage.className = 'status-message status-error';
            }
        }

        // Démarrer la caméra
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        width: 640, 
                        height: 480,
                        facingMode: 'user'
                    } 
                });
                video.srcObject = stream;
                startCameraBtn.disabled = true;
                verifyFaceBtn.disabled = false;
                
                statusMessage.textContent = 'Caméra activée. Positionnez votre visage au centre.';
                statusMessage.className = 'status-message status-info';
                statusMessage.style.display = 'block';

                // Détection continue du visage
                detectFace();
            } catch (error) {
                console.error('Erreur lors de l\'accès à la caméra:', error);
                statusMessage.textContent = 'Impossible d\'accéder à la caméra. Vérifiez les permissions.';
                statusMessage.className = 'status-message status-error';
                statusMessage.style.display = 'block';
            }
        }

        // Détection continue du visage
        async function detectFace() {
            if (!modelsLoaded || !stream || isVerifying) return;

            const detection = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (detection) {
                // Dessiner le rectangle de détection
                const ctx = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                const box = detection.detection.box;
                ctx.strokeStyle = '#00ff00';
                ctx.lineWidth = 2;
                ctx.strokeRect(box.x, box.y, box.width, box.height);
            }

            requestAnimationFrame(detectFace);
        }

        // Vérifier le visage
        async function verifyFace() {
            if (!modelsLoaded) {
                statusMessage.textContent = 'Les modèles ne sont pas encore chargés. Veuillez patienter.';
                statusMessage.className = 'status-message status-error';
                statusMessage.style.display = 'block';
                return;
            }

            if (isVerifying) return;
            isVerifying = true;

            try {
                statusMessage.textContent = 'Vérification en cours...';
                statusMessage.className = 'status-message status-info';
                statusMessage.style.display = 'block';
                verifyFaceBtn.disabled = true;

                const detection = await faceapi
                    .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (detection) {
                    const faceEmbedding = Array.from(detection.descriptor);

                    const response = await fetch('../controlleur/face_verify_traitement.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            face_embedding: faceEmbedding
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        statusMessage.textContent = 'Connexion réussie ! Redirection...';
                        statusMessage.className = 'status-message status-success';
                        
                        // Arrêter la caméra
                        if (stream) {
                            stream.getTracks().forEach(track => track.stop());
                        }
                        
                        setTimeout(() => {
                            window.location.href = 'dashboard.php';
                        }, 1500);
                    } else {
                        statusMessage.textContent = result.message || 'Visage non reconnu. Veuillez réessayer.';
                        statusMessage.className = 'status-message status-error';
                        verifyFaceBtn.disabled = false;
                        isVerifying = false;
                    }
                } else {
                    statusMessage.textContent = 'Aucun visage détecté. Veuillez réessayer.';
                    statusMessage.className = 'status-message status-error';
                    verifyFaceBtn.disabled = false;
                    isVerifying = false;
                }
            } catch (error) {
                console.error('Erreur lors de la vérification:', error);
                statusMessage.textContent = 'Erreur lors de la vérification. Veuillez réessayer.';
                statusMessage.className = 'status-message status-error';
                verifyFaceBtn.disabled = false;
                isVerifying = false;
            }
        }

        // Event listeners
        startCameraBtn.addEventListener('click', startCamera);
        verifyFaceBtn.addEventListener('click', verifyFace);

        // Charger les modèles au chargement de la page
        loadModels();
    </script>
</body>
</html>



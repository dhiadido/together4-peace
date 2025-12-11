<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(to right, #3498db, #2c3e50);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .form-container {
            padding: 40px;
        }
        
        .input-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 14px;
        }
        
        .input-group input {
            width: 100%;
            padding: 14px 45px 14px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .input-group input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 40px;
            cursor: pointer;
            color: #666;
            font-size: 18px;
        }
        
        .password-strength {
            height: 5px;
            background: #e0e0e0;
            border-radius: 3px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .strength-meter {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
        }
        
        .requirements {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .requirements ul {
            list-style: none;
            margin-top: 5px;
        }
        
        .requirements li {
            margin-bottom: 3px;
            display: flex;
            align-items: center;
        }
        
        .requirements li:before {
            content: "✗";
            color: #e74c3c;
            margin-right: 8px;
        }
        
        .requirements li.valid:before {
            content: "✓";
            color: #2ecc71;
        }
        
        .btn {
            background: linear-gradient(to right, #3498db, #2c3e50);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(52, 152, 219, 0.2);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔄 Nouveau mot de passe</h1>
            <p>Choisissez un nouveau mot de passe sécurisé</p>
        </div>
        
        <div class="form-container">
            <?php 
            if(isset($_GET['token']) && isset($_GET['email'])) {
                $token = htmlspecialchars($_GET['token']);
                $email = htmlspecialchars($_GET['email']);
            } else {
                header('Location: forgot-password.php');
                exit();
            }
            ?>
            
            <?php if(isset($_GET['error'])): ?>
                <div class="message error">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            
            <form action="controlleur/reset_password_traitement.php" method="POST" id="resetForm">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                
                <div class="input-group">
                    <label for="password">Nouveau mot de passe :</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Entrez votre nouveau mot de passe">
                    <span class="password-toggle" id="togglePassword1">👁️</span>
                    <div class="password-strength">
                        <div class="strength-meter" id="strengthMeter"></div>
                    </div>
                    <div class="requirements">
                        Le mot de passe doit contenir :
                        <ul id="requirements">
                            <li id="length">Au moins 8 caractères</li>
                            <li id="uppercase">Une lettre majuscule</li>
                            <li id="lowercase">Une lettre minuscule</li>
                            <li id="number">Un chiffre</li>
                            <li id="special">Un caractère spécial</li>
                        </ul>
                    </div>
                </div>
                
                <div class="input-group">
                    <label for="confirm_password">Confirmer le mot de passe :</label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                           placeholder="Confirmez votre nouveau mot de passe">
                    <span class="password-toggle" id="togglePassword2">👁️</span>
                    <div id="passwordMatch" style="font-size:12px; margin-top:5px;"></div>
                </div>
                
                <button type="submit" class="btn" id="submitBtn" disabled>Réinitialiser le mot de passe</button>
            </form>
        </div>
    </div>
    
    <script>
        // Basculer la visibilité des mots de passe
        document.getElementById('togglePassword1').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
        });
        
        document.getElementById('togglePassword2').addEventListener('click', function() {
            const passwordInput = document.getElementById('confirm_password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
        });
        
        // Vérification de la force du mot de passe
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');
        const strengthMeter = document.getElementById('strengthMeter');
        const requirements = document.getElementById('requirements').children;
        const submitBtn = document.getElementById('submitBtn');
        
        function checkPasswordStrength(password) {
            let strength = 0;
            
            // Vérifier chaque condition
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            
            // Mettre à jour l'interface
            requirements[0].classList.toggle('valid', hasLength);
            requirements[1].classList.toggle('valid', hasUppercase);
            requirements[2].classList.toggle('valid', hasLowercase);
            requirements[3].classList.toggle('valid', hasNumber);
            requirements[4].classList.toggle('valid', hasSpecial);
            
            // Calculer la force
            if (hasLength) strength += 20;
            if (hasUppercase) strength += 20;
            if (hasLowercase) strength += 20;
            if (hasNumber) strength += 20;
            if (hasSpecial) strength += 20;
            
            // Mettre à jour la barre de force
            strengthMeter.style.width = strength + '%';
            
            if (strength < 40) {
                strengthMeter.style.backgroundColor = '#e74c3c';
            } else if (strength < 80) {
                strengthMeter.style.backgroundColor = '#f39c12';
            } else {
                strengthMeter.style.backgroundColor = '#2ecc71';
            }
            
            return strength;
        }
        
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmInput.value;
            const matchElement = document.getElementById('passwordMatch');
            
            if (confirmPassword === '') {
                matchElement.textContent = '';
                matchElement.style.color = '';
                return false;
            }
            
            if (password === confirmPassword) {
                matchElement.textContent = '✓ Les mots de passe correspondent';
                matchElement.style.color = '#2ecc71';
                return true;
            } else {
                matchElement.textContent = '✗ Les mots de passe ne correspondent pas';
                matchElement.style.color = '#e74c3c';
                return false;
            }
        }
        
        function validateForm() {
            const strength = checkPasswordStrength(passwordInput.value);
            const match = checkPasswordMatch();
            
            // Activer le bouton seulement si le mot de passe est fort et correspond
            if (strength >= 80 && match) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }
        
        passwordInput.addEventListener('input', validateForm);
        confirmInput.addEventListener('input', validateForm);
        
        // Empêcher la soumission si validation échoue
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            if (submitBtn.disabled) {
                e.preventDefault();
                alert('Veuillez remplir tous les critères de sécurité');
            }
        });
    </script>
</body>
</html>
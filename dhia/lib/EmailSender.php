<?php
// Installation MANUELLE de PHPMailer
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../config/EmailConfig.php';

class EmailSender {
    
    public static function sendVerificationCode($email, $code, $userName = '') {
        $mail = new PHPMailer(true);
        
        try {
            // Configuration SMTP Gmail
            $mail->isSMTP();
            $mail->Host       = EmailConfig::SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = EmailConfig::SMTP_USERNAME;
            $mail->Password   = EmailConfig::SMTP_PASSWORD;
            $mail->SMTPSecure = EmailConfig::SMTP_SECURE;
            $mail->Port       = EmailConfig::SMTP_PORT;
            $mail->CharSet    = 'UTF-8';
            
            // Désactiver la vérification SSL stricte
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            // Destinataires
            $mail->setFrom(EmailConfig::FROM_EMAIL, EmailConfig::FROM_NAME);
            $mail->addAddress($email, $userName);
            
            // Contenu de l'email
            $mail->isHTML(true);
            $mail->Subject = 'Code de verification - Together4Peace';
            
            $mail->Body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { 
                        background: linear-gradient(135deg, #002d62 0%, #00aaff 100%); 
                        color: white; 
                        padding: 30px; 
                        text-align: center; 
                        border-radius: 10px 10px 0 0; 
                    }
                    .content { 
                        background: #f9f9f9; 
                        padding: 30px; 
                        border-radius: 0 0 10px 10px; 
                    }
                    .code-box { 
                        background: white; 
                        border: 2px solid #002d62; 
                        border-radius: 10px; 
                        padding: 20px; 
                        text-align: center; 
                        margin: 20px 0; 
                    }
                    .code { 
                        font-size: 32px; 
                        font-weight: bold; 
                        color: #002d62; 
                        letter-spacing: 5px; 
                    }
                    .footer { 
                        text-align: center; 
                        margin-top: 20px; 
                        font-size: 12px; 
                        color: #666; 
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>🔐 Réinitialisation de mot de passe</h1>
                    </div>
                    <div class='content'>
                        <p>Bonjour" . ($userName ? " <strong>$userName</strong>" : "") . ",</p>
                        <p>Vous avez demandé à réinitialiser votre mot de passe sur <strong>Together4Peace</strong>.</p>
                        <p>Voici votre code de vérification :</p>
                        
                        <div class='code-box'>
                            <div class='code'>$code</div>
                        </div>
                        
                        <p><strong>⚠️ Ce code est valide pendant 15 minutes.</strong></p>
                        <p>Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email.</p>
                        
                        <div class='footer'>
                            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                            <p>&copy; 2025 Together4Peace - Tous droits réservés</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            $mail->AltBody = "Votre code de verification Together4Peace est : $code\n\nCe code est valide pendant 15 minutes.";
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email a $email: {$mail->ErrorInfo}");
            return false;
        }
    }
}
?>
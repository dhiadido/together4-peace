
<?php
class EmailConfig {
    // Configuration Gmail SMTP
    const SMTP_HOST = 'smtp.gmail.com';
    const SMTP_PORT = 587;  // TLS
    const SMTP_USERNAME = 'togetherpeace3@gmail.com';
    const SMTP_PASS = 'ioshipfikqikhwaf';  // Mot de passe d'application
    
    // Ou pour SSL (port 465)
    // const SMTP_PORT = 465;
    
    const FROM_EMAIL = 'togetherpeace3@gmail.com';  // Doit être le même que SMTP_USER
    const FROM_NAME = 'Support VotreSite';
    
    public static function getConfig() {
        return [
            'host' => self::SMTP_HOST,
            'port' => self::SMTP_PORT,
            'username' => self::SMTP_USER,
            'password' => self::SMTP_PASS,
            'encryption' => self::SMTP_PORT == 587 ? 'tls' : 'ssl',
            'from' => [
                'email' => self::FROM_EMAIL,
                'name' => self::FROM_NAME
            ]
        ];
    }
}
?>
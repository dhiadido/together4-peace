
<?php
class EmailConfig {
    // Configuration Mailtrap SMTP
    const SMTP_HOST = 'smtp.mailtrap.io';
    const SMTP_PORT = 587;  // Port Mailtrap avec TLS (alternative: 2525 sans TLS)
    const SMTP_USERNAME = 'aaa8a4706f1ac4';
    const SMTP_PASSWORD = '9578a8e89152c0';
    const SMTP_SECURE = 'tls';  // TLS pour Mailtrap
    
    // Email expéditeur
    const FROM_EMAIL = 'noreply@together4peace.com';
    const FROM_NAME = 'Together4Peace - Support';
    
    public static function getConfig() {
        return [
            'host' => self::SMTP_HOST,
            'port' => self::SMTP_PORT,
            'username' => self::SMTP_USERNAME,
            'password' => self::SMTP_PASSWORD,
            'encryption' => 'tls',  // Mailtrap utilise TLS
            'from' => [
                'email' => self::FROM_EMAIL,
                'name' => self::FROM_NAME
            ]
        ];
    }
}
?>
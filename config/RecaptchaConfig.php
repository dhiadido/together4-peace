<?php
// config/RecaptchaConfig.php

class RecaptchaConfig {
    // Clé de site reCAPTCHA (publique)
    const SITE_KEY = '6LdFyCcsAAAAAEV6ZrchO9nWUb51m1W9BeSFPXD2';
    
    // Clé secrète reCAPTCHA (privée)
    const SECRET_KEY = '6LdFyCcsAAAAAHUyhRZ9BJJbjRWmiWLwnNzcT-F-';
    
    // URL de vérification reCAPTCHA
    const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    
    /**
     * Vérifier la réponse reCAPTCHA
     * @param string $response Le token reCAPTCHA reçu du formulaire
     * @param string $remoteIp L'adresse IP de l'utilisateur (optionnel)
     * @return array ['success' => bool, 'error' => string|null]
     */
    public static function verify($response, $remoteIp = null) {
        if (empty($response)) {
            return ['success' => false, 'error' => 'reCAPTCHA non rempli'];
        }
        
        $data = [
            'secret' => self::SECRET_KEY,
            'response' => $response
        ];
        
        if ($remoteIp) {
            $data['remoteip'] = $remoteIp;
        }
        
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents(self::VERIFY_URL, false, $context);
        
        if ($result === false) {
            return ['success' => false, 'error' => 'Erreur de connexion avec reCAPTCHA'];
        }
        
        $json = json_decode($result, true);
        
        if ($json === null) {
            return ['success' => false, 'error' => 'Réponse invalide de reCAPTCHA'];
        }
        
        return [
            'success' => isset($json['success']) && $json['success'] === true,
            'error' => isset($json['error-codes']) ? implode(', ', $json['error-codes']) : null
        ];
    }
}
?>


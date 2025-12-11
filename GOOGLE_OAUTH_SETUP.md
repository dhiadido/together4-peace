# Configuration de l'authentification Google OAuth

## Étapes de configuration

### 1. Configuration de la base de données

Exécutez le script SQL `add_google_id_column.sql` dans votre base de données MySQL (via phpMyAdmin ou ligne de commande) :

```sql
USE baseuser;

ALTER TABLE `user2` 
ADD COLUMN `google_id` VARCHAR(255) NULL DEFAULT NULL AFTER `email`;

ALTER TABLE `user2` 
ADD COLUMN `photo` VARCHAR(500) NULL DEFAULT NULL AFTER `google_id`;

CREATE UNIQUE INDEX `idx_google_id` ON `user2` (`google_id`);
```

### 2. Configuration dans Google Cloud Console

1. Allez sur [Google Cloud Console](https://console.cloud.google.com/)
2. Créez un nouveau projet ou sélectionnez un projet existant
3. Activez l'API "Google+ API" ou "Google Identity API"
4. Allez dans "Identifiants" > "Créer des identifiants" > "ID client OAuth 2.0"
5. Configurez l'écran de consentement OAuth si nécessaire
6. Ajoutez l'URI de redirection autorisé :
   - Pour le développement local : `http://localhost/dhia/controlleur/google_callback.php`
   - Pour la production : `https://votre-domaine.com/controlleur/google_callback.php`
7. Copiez le **Client ID** et le **Client Secret**

### 3. Vérification de l'URI de redirection

L'URI de redirection doit correspondre exactement à celle configurée dans Google Cloud Console. 

Pour vérifier l'URI générée automatiquement, vous pouvez temporairement ajouter ce code dans `google_auth.php` :

```php
echo GoogleConfig::getRedirectUri();
exit;
```

Assurez-vous que cette URI correspond exactement à celle configurée dans Google Cloud Console.

### 4. Test de l'authentification

1. Allez sur la page de connexion : `http://localhost/dhia/views/login.php`
2. Cliquez sur le bouton "Se connecter avec Google"
3. Autorisez l'application dans la fenêtre Google
4. Vous serez redirigé vers le dashboard après connexion réussie

## Notes importantes

- Les credentials Google sont déjà configurés dans `config/GoogleConfig.php`
- Si vous changez de domaine ou d'environnement, mettez à jour l'URI de redirection dans Google Cloud Console
- La première connexion avec Google créera automatiquement un compte utilisateur
- Les utilisateurs existants peuvent lier leur compte Google en se connectant avec Google


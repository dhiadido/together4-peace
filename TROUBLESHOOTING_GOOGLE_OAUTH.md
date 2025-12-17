# Dépannage - Authentification Google OAuth

## Erreur 400 : "invalid_request"

Cette erreur indique généralement que la requête OAuth est mal formée. Voici les causes les plus courantes :

### 1. URI de redirection incorrecte

**Problème** : L'URI de redirection dans votre code ne correspond pas exactement à celle configurée dans Google Cloud Console.

**Solution** :
1. Accédez à `http://localhost/dhia/controlleur/test_google_redirect.php` pour voir l'URI générée
2. Copiez cette URI exactement
3. Allez dans Google Cloud Console > APIs & Services > Identifiants
4. Sélectionnez votre ID client OAuth 2.0
5. Dans "URIs de redirection autorisés", ajoutez l'URI (elle doit correspondre EXACTEMENT, caractère par caractère)
6. Assurez-vous que le protocole (http/https) correspond

### 2. Client ID incorrect

**Problème** : Le Client ID fourni (`6LeTwCcsAAAAAGN_YyOEltwUm2CQ8r4GWUQG7JSP`) ressemble à une clé **reCAPTCHA** plutôt qu'à un Client ID OAuth Google.

**Format attendu** : Les Client IDs OAuth Google ont généralement le format :
```
xxxxx-xxxxx.apps.googleusercontent.com
```

**Solution** :
1. Allez sur [Google Cloud Console](https://console.cloud.google.com/)
2. Naviguez vers : APIs & Services > Identifiants
3. Créez un nouveau "ID client OAuth 2.0" (pas reCAPTCHA)
4. Sélectionnez "Application Web"
5. Configurez l'écran de consentement OAuth si nécessaire
6. Copiez le nouveau Client ID et Client Secret
7. Mettez à jour `config/GoogleConfig.php` avec les nouvelles valeurs

### 3. Client Secret incorrect

**Problème** : Le Client Secret ne correspond pas au Client ID.

**Solution** : Vérifiez que le Client Secret dans `config/GoogleConfig.php` correspond bien au Client ID dans Google Cloud Console.

### 4. Scopes incorrects

**Problème** : Les scopes demandés ne sont pas autorisés.

**Solution** : Les scopes utilisés dans le code sont corrects :
- `https://www.googleapis.com/auth/userinfo.email`
- `https://www.googleapis.com/auth/userinfo.profile`

Assurez-vous que l'API "Google+ API" ou "Google Identity API" est activée dans Google Cloud Console.

## Vérification étape par étape

1. **Vérifier l'URI de redirection** :
   ```
   http://localhost/dhia/controlleur/test_google_redirect.php
   ```

2. **Vérifier dans Google Cloud Console** :
   - L'URI de redirection correspond exactement
   - Le Client ID est bien un ID OAuth 2.0 (pas reCAPTCHA)
   - L'API Google Identity est activée

3. **Vérifier les logs d'erreur PHP** :
   - Consultez les logs d'erreur de XAMPP pour plus de détails
   - Les erreurs sont également loggées dans le code

## Configuration correcte dans Google Cloud Console

1. **Créer un ID client OAuth 2.0** :
   - Type : Application Web
   - Nom : Together4Peace (ou votre choix)
   - URIs de redirection autorisés : `http://localhost/dhia/controlleur/google_callback.php`

2. **Activer les APIs nécessaires** :
   - Google+ API (ou Google Identity API)
   - Google People API (optionnel, pour plus d'informations)

3. **Configurer l'écran de consentement OAuth** :
   - Type d'utilisateur : Externe (pour les tests)
   - Informations de l'application : Remplissez les champs requis
   - Scopes : Les scopes par défaut incluent email et profile

## Test de l'authentification

Après avoir corrigé les problèmes :

1. Exécutez le script SQL `add_google_id_column.sql`
2. Vérifiez l'URI avec `test_google_redirect.php`
3. Configurez l'URI dans Google Cloud Console
4. Testez la connexion depuis `login.php`

## Support

Si le problème persiste, vérifiez :
- Les logs d'erreur PHP
- La console du navigateur (F12) pour les erreurs JavaScript
- Les logs dans Google Cloud Console > APIs & Services > Quotas


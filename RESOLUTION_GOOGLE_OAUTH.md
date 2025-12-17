# 🔧 Résolution du problème Google OAuth - Erreur 400: invalid_request

## Problème
Lors du clic sur "Se connecter avec Google", vous obtenez l'erreur :
- **Erreur 400: invalid_request**
- "Accès bloqué : erreur d'autorisation"

## Cause principale
L'URI de redirection dans votre code ne correspond **pas exactement** à celle configurée dans Google Cloud Console.

## Solution étape par étape

### 1. Vérifier l'URI de redirection générée

1. Allez sur : `http://votre-domaine/controlleur/test_google_redirect_uri.php`
   - Remplacez `votre-domaine` par votre domaine (ex: `localhost` ou `localhost/dhia`)
   
2. Copiez l'URI affichée (la première méthode)

### 2. Configurer dans Google Cloud Console

1. Allez sur [Google Cloud Console - Credentials](https://console.cloud.google.com/apis/credentials)

2. Sélectionnez votre projet

3. Cliquez sur votre **OAuth 2.0 Client ID** (celui avec le CLIENT_ID : `292796039438-2v7alsvanp8qvp1hdojosgatoal5bv31`)

4. Dans la section **"Authorized redirect URIs"**, cliquez sur **"ADD URI"**

5. Collez l'URI que vous avez copiée à l'étape 1

6. Cliquez sur **"SAVE"**

### 3. Vérifications importantes

✅ **L'URI doit correspondre EXACTEMENT** :
- Même protocole (http ou https)
- Même domaine/host
- Même chemin complet
- Pas d'espace, pas de slash final supplémentaire

✅ **Exemples d'URI correctes** :
- `http://localhost/controlleur/google_callback.php`
- `http://localhost/dhia/controlleur/google_callback.php`
- `https://votre-domaine.com/controlleur/google_callback.php`

### 4. Vérifier l'écran de consentement OAuth

1. Dans Google Cloud Console, allez dans **"OAuth consent screen"**

2. Assurez-vous que :
   - L'application est en mode **"Testing"** ou **"In production"**
   - Si en mode Testing, votre email (`dhiaeddineboujemaa@gmail.com`) est dans la liste des **"Test users"**

3. Les scopes suivants sont autorisés :
   - `openid`
   - `email`
   - `profile`

### 5. Attendre la propagation

Après avoir modifié les paramètres dans Google Cloud Console :
- ⏱️ Attendez **5-10 minutes** pour que les changements prennent effet
- 🔄 Videz le cache de votre navigateur
- 🔄 Réessayez la connexion

### 6. Tester à nouveau

1. Allez sur la page de connexion
2. Cliquez sur "Se connecter avec Google"
3. Si l'erreur persiste, vérifiez les logs d'erreur PHP

## Corrections apportées au code

✅ **Amélioration de la génération de l'URI** :
- Méthode principale plus robuste
- Méthode alternative simple en cas d'échec
- Meilleure gestion des chemins

✅ **Validation de l'email** :
- Vérification que l'email n'est pas vide
- Validation du format de l'email
- Suppression automatique des espaces dans l'email

✅ **Amélioration des scopes** :
- Ajout du scope `openid`
- Ajout de `prompt: consent` pour forcer le consentement

✅ **Script de débogage** :
- Page de test pour afficher l'URI générée
- Informations détaillées sur la configuration serveur

## Si le problème persiste

1. **Vérifiez les logs PHP** :
   - Regardez les fichiers de log PHP pour voir les erreurs détaillées
   - Les logs contiennent l'URI générée

2. **Vérifiez que le Client ID et Secret sont corrects** :
   - Dans `config/GoogleConfig.php`
   - Correspondent à ceux dans Google Cloud Console

3. **Testez avec un autre compte Google** :
   - Si l'application est en mode Testing, assurez-vous que le compte est dans les test users

4. **Vérifiez les permissions du compte Google** :
   - Le compte doit avoir accès à l'email et au profil

## Contact

Si le problème persiste après avoir suivi ces étapes, vérifiez :
- Les logs d'erreur PHP
- Les logs dans Google Cloud Console
- La configuration de votre serveur web


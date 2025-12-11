# Guide : Créer un Client ID OAuth Google

## ⚠️ Problème identifié

Le Client ID actuel (`6LeTwCcsAAAAAGN_YyOEltwUm2CQ8r4GWUQG7JSP`) est une **clé reCAPTCHA**, pas un Client ID OAuth. C'est pourquoi vous obtenez l'erreur "invalid_client".

## ✅ Solution : Créer un vrai Client ID OAuth 2.0

### Étape 1 : Accéder à Google Cloud Console

1. Allez sur [Google Cloud Console](https://console.cloud.google.com/)
2. Connectez-vous avec votre compte Google (kacemrayen059@gmail.com)

### Étape 2 : Créer ou sélectionner un projet

1. En haut de la page, cliquez sur le sélecteur de projet
2. Cliquez sur **"NOUVEAU PROJET"** (ou sélectionnez un projet existant)
3. Nom du projet : `Together4Peace` (ou votre choix)
4. Cliquez sur **"CRÉER"**

### Étape 3 : Activer les APIs nécessaires

1. Dans le menu de gauche, allez dans **"APIs et services"** > **"Bibliothèque"**
2. Recherchez **"Google+ API"** ou **"Google Identity API"**
3. Cliquez dessus et cliquez sur **"ACTIVER"**
4. (Optionnel) Activez aussi **"People API"** pour plus d'informations utilisateur

### Étape 4 : Configurer l'écran de consentement OAuth

1. Allez dans **"APIs et services"** > **"Écran de consentement OAuth"**
2. Sélectionnez **"Externe"** (pour les tests) et cliquez sur **"CRÉER"**
3. Remplissez les informations :
   - **Nom de l'application** : Together4Peace
   - **Adresse e-mail de support utilisateur** : votre email
   - **Adresse e-mail du développeur** : votre email
4. Cliquez sur **"ENREGISTRER ET CONTINUER"**
5. Dans **"Scopes"**, cliquez sur **"ENREGISTRER ET CONTINUER"** (les scopes par défaut suffisent)
6. Dans **"Utilisateurs de test"**, ajoutez votre email si nécessaire, puis **"ENREGISTRER ET CONTINUER"**
7. Cliquez sur **"RETOUR AU TABLEAU DE BORD"**

### Étape 5 : Créer l'ID client OAuth 2.0

1. Allez dans **"APIs et services"** > **"Identifiants"**
2. Cliquez sur **"CRÉER DES IDENTIFIANTS"** en haut
3. Sélectionnez **"ID client OAuth 2.0"**

### Étape 6 : Configurer l'ID client

1. **Type d'application** : Sélectionnez **"Application Web"**
2. **Nom** : `Together4Peace Web Client` (ou votre choix)
3. **URIs de redirection autorisés** : 
   - Cliquez sur **"+ AJOUTER UN URI"**
   - Ajoutez : `http://localhost/dhia/controlleur/google_callback.php`
   - ⚠️ **IMPORTANT** : L'URI doit correspondre EXACTEMENT (pas d'espace, même protocole)
4. Cliquez sur **"CRÉER"**

### Étape 7 : Copier les identifiants

Après la création, vous verrez une fenêtre avec :
- **Votre ID client** : Format `xxxxx-xxxxx.apps.googleusercontent.com`
- **Votre secret client** : Format `GOCSPX-xxxxx...`

⚠️ **Copiez-les immédiatement** car le secret ne sera plus affiché !

### Étape 8 : Mettre à jour la configuration

1. Ouvrez le fichier `config/GoogleConfig.php`
2. Remplacez les valeurs :

```php
const CLIENT_ID = 'VOTRE_NOUVEAU_CLIENT_ID.apps.googleusercontent.com';
const CLIENT_SECRET = 'VOTRE_NOUVEAU_CLIENT_SECRET';
```

3. Enregistrez le fichier

### Étape 9 : Vérifier l'URI de redirection

1. Accédez à : `http://localhost/dhia/controlleur/test_google_redirect.php`
2. Copiez l'URI affichée
3. Retournez dans Google Cloud Console > Identifiants > Votre ID client
4. Vérifiez que l'URI dans "URIs de redirection autorisés" correspond EXACTEMENT
5. Si ce n'est pas le cas, modifiez-la pour qu'elle corresponde

### Étape 10 : Tester

1. Allez sur `http://localhost/dhia/views/login.php`
2. Cliquez sur **"Se connecter avec Google"**
3. Autorisez l'application
4. Vous devriez être redirigé vers le dashboard

## 📝 Format attendu

- **Client ID OAuth** : `123456789-abcdefghijklmnop.apps.googleusercontent.com`
- **Client Secret** : `GOCSPX-xxxxxxxxxxxxxxxxxxxxx`
- **Clé reCAPTCHA** (❌ ne fonctionne pas) : `6LeTwCcsAAAAAGN_YyOEltwUm2CQ8r4GWUQG7JSP`

## 🔍 Vérification

Pour vérifier que vous avez le bon type d'identifiant :
- ✅ Client ID OAuth : se termine par `.apps.googleusercontent.com`
- ❌ Clé reCAPTCHA : commence souvent par `6L` et ne se termine pas par `.apps.googleusercontent.com`

## ⚠️ Notes importantes

1. Le Client ID et le Client Secret doivent provenir du **même** ID client OAuth 2.0
2. L'URI de redirection doit correspondre **exactement** (caractère par caractère)
3. Pour la production, vous devrez ajouter une autre URI avec `https://`
4. Gardez vos identifiants secrets et ne les partagez jamais publiquement


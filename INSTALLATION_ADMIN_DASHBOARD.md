# 📋 Installation et Configuration du Dashboard Admin

## Problème résolu : Colonne `date_inscription` manquante

L'erreur **"Unknown column 'date_inscription' in 'field list'"** a été corrigée. Le code gère maintenant automatiquement l'absence de cette colonne.

## ✅ Solution automatique

Le code vérifie maintenant si la colonne `date_inscription` existe avant de l'utiliser. Si elle n'existe pas, le système fonctionne quand même mais affiche "N/A" pour la date d'inscription.

## 🔧 Option 1 : Ajouter la colonne (recommandé)

Pour avoir les dates d'inscription complètes, exécutez ce script SQL dans votre base de données :

```sql
ALTER TABLE `user2` 
ADD COLUMN `date_inscription` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `role`;

UPDATE `user2` SET `date_inscription` = NOW() WHERE `date_inscription` IS NULL;
```

**Ou utilisez le fichier** : `add_date_inscription_column.sql`

### Comment exécuter le script :

1. **Via phpMyAdmin** :
   - Ouvrez phpMyAdmin
   - Sélectionnez votre base de données
   - Cliquez sur l'onglet "SQL"
   - Copiez-collez le script ci-dessus
   - Cliquez sur "Exécuter"

2. **Via ligne de commande MySQL** :
   ```bash
   mysql -u root -p votre_base_de_donnees < add_date_inscription_column.sql
   ```

## 📊 Fonctionnalités du Dashboard Admin

### ✅ Statistiques
- Total des utilisateurs
- Nombre d'administrateurs
- Inscriptions des 7 derniers jours

### ✅ Gestion des utilisateurs
- **Créer** : Formulaire pour créer un nouveau compte
- **Modifier** : Cliquez sur "Modifier" pour éditer un utilisateur
- **Supprimer** : Bouton de suppression avec confirmation

### ✅ Validation
- Nom requis (non vide)
- Email requis et sans espaces
- Validation en temps réel

## 🔐 Connexion Admin

1. Allez sur la page de connexion admin : `views/admin-login.php`
2. Connectez-vous avec un compte ayant le rôle `admin`
3. Vous accéderez au dashboard avec toutes les fonctionnalités

## ⚠️ Notes importantes

- **Protection** : Un admin ne peut pas supprimer son propre compte
- **Validation** : Tous les champs sont validés côté client et serveur
- **Sécurité** : Les mots de passe sont hashés avec bcrypt

## 🐛 Dépannage

### Si le dashboard ne s'affiche pas :
1. Vérifiez que vous êtes connecté en tant qu'admin
2. Vérifiez que la session `admin_id` est définie
3. Vérifiez les logs PHP pour les erreurs

### Si les statistiques ne s'affichent pas :
1. Vérifiez que la colonne `date_inscription` existe (ou utilisez l'option 1 ci-dessus)
2. Le système fonctionne même sans cette colonne, mais affichera "N/A"

### Si vous ne pouvez pas créer/modifier des utilisateurs :
1. Vérifiez que tous les champs requis sont remplis
2. Vérifiez que l'email n'existe pas déjà
3. Vérifiez les logs PHP pour les erreurs SQL

## 📝 Structure de la table user2

Colonnes requises :
- `id_utilisateur` (PRIMARY KEY)
- `nom` (VARCHAR)
- `prenom` (VARCHAR, optionnel)
- `email` (VARCHAR, UNIQUE)
- `mot_de_passe` (VARCHAR)
- `role` (VARCHAR, 'user' ou 'admin')
- `date_inscription` (DATETIME, optionnel mais recommandé)
- `google_id` (VARCHAR, optionnel)
- `photo` (VARCHAR, optionnel)
- `face_embedding` (TEXT, optionnel)


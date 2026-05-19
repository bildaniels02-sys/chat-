# 🛠️ Guide d'installation et utilisation de la base de données

## 📋 Table des matières
1. [Installation automatique](#installation-automatique)
2. [Installation manuelle](#installation-manuelle)
3. [Structure de la base de données](#structure-de-la-base-de-données)
4. [Fichiers créés](#fichiers-créés)
5. [Guide d'utilisation](#guide-dutilisation)
6. [Dépannage](#dépannage)

---

## 🚀 Installation automatique (Recommandé)

### Étape 1: Démarrer MAMP
1. Ouvrez **MAMP** depuis votre ordinateur
2. Assurez-vous que les services **Apache** et **MySQL** sont actifs ✓

### Étape 2: Exécuter le script d'installation
1. Ouvrez votre navigateur web
2. Allez à: `http://localhost/chatv1/install.php`
3. Un message de confirmation s'affichera

```
✓ Base de données créée/vérifiée avec succès.
✓ Table 'users' créée/vérifiée avec succès.
✓ Table 'messages' créée/vérifiée avec succès.
✓ Table 'sessions' créée/vérifiée avec succès.
✓ Utilisateurs de test insérés/vérifiés avec succès.
✓ Installation terminée avec succès!
```

### Étape 3: Tester la connexion
1. Cliquez sur le lien: "Aller à la page de connexion"
2. Utilisez un compte de test:
   - **Email**: `user@example.com`
   - **Mot de passe**: `password123`

---

## 🔧 Installation manuelle

### Étape 1: Créer la base de données via phpMyAdmin
1. Ouvrez phpMyAdmin: `http://localhost/phpmyadmin`
2. Connectez-vous avec:
   - **Utilisateur**: `root`
   - **Mot de passe**: `root`
3. Cliquez sur "Nouvelle base de données"
4. Nommez-la: `chat_app`
5. Cliquez sur "Créer"

### Étape 2: Importer le schéma SQL
1. Allez à l'onglet "SQL" de la base de données `chat_app`
2. Ouvrez le fichier [database.sql](database.sql)
3. Copiez tout le contenu
4. Collez-le dans phpMyAdmin
5. Cliquez sur "Exécuter"

---

## 📊 Structure de la base de données

### Table: `users`
Stocke les informations des utilisateurs

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | INT | Identifiant unique |
| `username` | VARCHAR(50) | Nom d'utilisateur unique |
| `email` | VARCHAR(100) | Email unique |
| `password` | VARCHAR(255) | Mot de passe hachéisé |
| `created_at` | TIMESTAMP | Date de création |
| `updated_at` | TIMESTAMP | Dernière mise à jour |
| `is_active` | BOOLEAN | Statut du compte |

### Table: `messages`
Stocke tous les messages du chat

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | INT | Identifiant unique |
| `user_id` | INT | Référence à l'utilisateur (clé étrangère) |
| `message` | TEXT | Contenu du message |
| `created_at` | TIMESTAMP | Date d'envoi |

### Table: `sessions`
Stocke les sessions utilisateur (optionnel, pour la sécurité)

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | INT | Identifiant unique |
| `user_id` | INT | Référence à l'utilisateur |
| `session_id` | VARCHAR(255) | ID de session |
| `ip_address` | VARCHAR(45) | Adresse IP |
| `user_agent` | TEXT | Navigateur utilisé |
| `created_at` | TIMESTAMP | Création |
| `last_activity` | TIMESTAMP | Dernière activité |
| `expires_at` | TIMESTAMP | Expiration |

---

## 📁 Fichiers créés

### Configuration et Connexion
- **[config.php](config.php)** - Paramètres de connexion à MySQL
- **[db_connect.php](db_connect.php)** - Fonctions de connexion et requêtes
- **[install.php](install.php)** - Script d'installation automatique
- **[database.sql](database.sql)** - Schéma SQL complet

### Authentification (Modifiés)
- **[authenticate.php](authenticate.php)** - Connexion avec vérification BD
- **[register_process.php](register_process.php)** - Inscription dans la BD
- **[login.php](login.php)** - Page de connexion avec messages

### Chat (Modifiés)
- **[chat.php](chat.php)** - Affiche les messages de la BD
- **[send_message.php](send_message.php)** - Envoie les messages à la BD
- **[logout.php](logout.php)** - Déconnexion

---

## 🎯 Guide d'utilisation

### 1️⃣ Première visite
```
Utilisateur → chat.php → Redirection login.php
```

### 2️⃣ Créer un compte
```
Clic "S'inscrire" → register.php → Remplir le formulaire
→ register_process.php → Insertion en BD → login.php
```

### 3️⃣ Se connecter
```
Formulaire login.php → authenticate.php → Vérification BD
→ Si OK: Session créée → chat.php
→ Si NON: Message d'erreur → login.php
```

### 4️⃣ Envoyer un message
```
Formulaire chat.php → send_message.php 
→ Insertion en BD → Redirection chat.php
→ Affichage du message
```

### 5️⃣ Se déconnecter
```
Bouton logout.php → Session détruite → login.php
```

---

## 🔐 Comptes de test

### Compte 1
- **Email**: `user@example.com`
- **Mot de passe**: `password123`
- **Nom d'utilisateur**: `User1`

### Compte 2
- **Email**: `test@test.com`
- **Mot de passe**: `test123`
- **Nom d'utilisateur**: `Test User`

---

## 🐛 Dépannage

### ❌ "Erreur de connexion à la base de données"

**Causes possibles**:
1. MySQL n'est pas actif dans MAMP
2. Les paramètres de [config.php](config.php) sont incorrects
3. La base de données n'existe pas

**Solutions**:
```
1. Vérifier que MAMP est actif (services Apache + MySQL verts)
2. Vérifier config.php:
   - DB_HOST: localhost
   - DB_USER: root
   - DB_PASS: root
   - DB_PORT: 3306
3. Exécuter install.php pour créer la BD
```

### ❌ "Table 'chat_app.users' doesn't exist"

**Cause**: Les tables n'ont pas été créées

**Solution**: Exécuter `http://localhost/chatv1/install.php`

### ❌ "Email ou mot de passe incorrect"

**Causes possibles**:
1. L'email n'existe pas
2. Le mot de passe est incorrect
3. Le compte est désactivé

**Solutions**:
```
1. Vérifier l'orthographe de l'email
2. Réinitialiser le mot de passe (futur)
3. S'inscrire avec un nouveau compte
```

### ❌ "Cet email est déjà utilisé"

**Cause**: L'email existe déjà dans la BD

**Solution**: Utilisez un autre email ou connectez-vous avec ce compte

---

## 📝 Configuration avancée

### Modifier les identifiants MySQL
Éditer [config.php](config.php):
```php
define('DB_USER', 'votre_utilisateur');
define('DB_PASS', 'votre_mot_de_passe');
```

### Modifier le port MySQL
```php
define('DB_PORT', 3307);  // Au lieu de 3306
```

### Activer/Désactiver le mode debug
```php
define('DEBUG_MODE', false);  // true pour debug, false en production
```

---

## ✅ Checklist de vérification

- [ ] MAMP est actif (Apache + MySQL verts)
- [ ] Les fichiers sont dans `C:\MAMP\htdocs\chatv1\`
- [ ] `install.php` a été exécuté avec succès
- [ ] La page de login s'affiche: `http://localhost/chatv1/login.php`
- [ ] Les comptes de test fonctionnent
- [ ] Les messages s'enregistrent en BD

---

## 🎓 Prochaines étapes

1. **Sécurité**:
   - Implémenter CSRF tokens
   - Ajouter la validation des entrées
   - Configurer les en-têtes de sécurité

2. **Fonctionnalités**:
   - Récupération de mot de passe
   - Suppression de compte
   - Liste des utilisateurs en ligne
   - Recherche dans les messages

3. **Performance**:
   - Ajouter la pagination
   - Implémenter le cache
   - Optimiser les requêtes SQL

---

**Besoin d'aide?** Consultez les fichiers de code ou contactez le support.

Créé le: 19 mai 2026

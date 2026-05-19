# Système d'authentification - Chat en direct

## Description
Un système d'authentification complet pour l'application de chat en direct avec pages de connexion, inscription et déconnexion.

## Fichiers créés

### 1. **login.php**
- Page de connexion (login)
- Formulaire avec champs email et mot de passe
- Lien vers la page d'inscription
- Style cohérent avec l'application

### 2. **register.php**
- Page d'inscription
- Formulaire avec champs: nom d'utilisateur, email, mot de passe, confirmation mot de passe
- Lien vers la page de connexion
- Validation côté client

### 3. **authenticate.php**
- Traitement de la connexion
- Vérifie les identifiants de l'utilisateur
- Crée une session utilisateur
- Redirige vers chat.php si succès, ou login.php si erreur

### 4. **register_process.php**
- Traitement de l'inscription
- Valide les données du formulaire
- Redirige vers login.php après succès

### 5. **logout.php**
- Gère la déconnexion
- Détruit la session utilisateur
- Redirige vers login.php

### 6. **chat.php** (modifié)
- Ajout de la vérification de session
- Affiche l'email de l'utilisateur connecté
- Bouton de déconnexion
- Redirige vers login.php si non connecté

## Flux d'utilisation

1. **Premier accès**: L'utilisateur accède à `chat.php` → redirigé vers `login.php`
2. **Pas de compte**: Clic sur "S'inscrire" → `register.php` → `register_process.php` → `login.php`
3. **Connexion**: Saisir identifiants → `authenticate.php` → `chat.php`
4. **Déconnexion**: Clic sur "Déconnexion" → `logout.php` → `login.php`

## Configuration nécessaire

### À faire:
- [ ] Connecter à une base de données (MySQL, SQLite, etc.)
- [ ] Implémenter le stockage des utilisateurs en base de données
- [ ] Ajouter le hachage des mots de passe (utiliser `password_hash()` et `password_verify()`)
- [ ] Ajouter une protection CSRF (tokens CSRF)
- [ ] Implémenter des validations plus robustes
- [ ] Ajouter des logs de sécurité

### Utilisateurs de test (actuellement en dur):
- Email: `user@example.com` | Mot de passe: `password123`
- Email: `test@test.com` | Mot de passe: `test123`

## Sécurité

**Important**: 
- Les identifiants sont actuellement en dur dans `authenticate.php` (à titre d'exemple)
- Il est nécessaire d'utiliser une base de données pour un environnement de production
- Utiliser `password_hash()` pour stocker les mots de passe
- Implémenter une protection CSRF pour les formulaires
- Valider et nettoyer toutes les entrées utilisateur

## Points d'améliorations futurs

- [ ] Oublier mot de passe
- [ ] Vérification email
- [ ] Authentification à deux facteurs
- [ ] Avatar utilisateur
- [ ] Profil utilisateur modifiable
- [ ] Logs d'accès

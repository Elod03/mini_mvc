# Installation du système d'authentification

Ce document explique comment installer et utiliser le système d'authentification pour l'application Mini MVC.

## 📋 Prérequis

- Base de données MySQL/MariaDB configurée
- PHP 7.4 ou supérieur avec l'extension PDO
- Les tables `user`, `produit`, `panier`, `commande` déjà créées

## 🔧 Installation

### 1. Mise à jour de la base de données

Exécutez le script SQL suivant pour ajouter la colonne `password` à la table `user` :

```sql
-- Ajouter la colonne password si elle n'existe pas déjà
ALTER TABLE user 
ADD COLUMN password VARCHAR(255) NULL;
```

Ou exécutez le fichier `database/auth_migration.sql` :

```bash
mysql -u root -p mini_mvc < database/auth_migration.sql
```

### 2. Configuration

Assurez-vous que les sessions PHP sont activées dans votre configuration PHP. Par défaut, elles le sont généralement.

## 🚀 Utilisation

### Inscription

1. Accédez à `/auth/register`
2. Remplissez le formulaire :
   - Nom
   - Email (doit être unique)
   - Mot de passe (minimum 6 caractères)
   - Confirmation du mot de passe
3. Après l'inscription, vous êtes automatiquement connecté

### Connexion

1. Accédez à `/auth/login`
2. Entrez votre email et mot de passe
3. Vous êtes redirigé vers la page d'accueil

### Déconnexion

Cliquez sur "Déconnexion" dans le menu de navigation (visible uniquement si vous êtes connecté)

## 🔒 Sécurité

- Les mots de passe sont hashés avec `password_hash()` (bcrypt)
- Les sessions sont utilisées pour maintenir l'état de connexion
- Les utilisateurs ne peuvent accéder qu'à leur propre panier et commandes
- Les formulaires nécessitent une authentification pour ajouter des produits au panier

## 📝 Fonctionnalités

### Pages protégées

Les pages suivantes nécessitent une authentification :
- `/cart` - Panier
- `/orders` - Liste des commandes
- `/orders/show` - Détails d'une commande

Si un utilisateur non authentifié tente d'accéder à ces pages, il est redirigé vers la page de connexion.

### Navigation

Le menu de navigation affiche :
- **Si connecté** : Nom de l'utilisateur, lien vers le panier, lien vers les commandes, bouton de déconnexion
- **Si non connecté** : Boutons de connexion et d'inscription

## 🐛 Dépannage

### Problème : "Session non démarrée"

Assurez-vous que les sessions PHP sont activées. Vérifiez votre `php.ini` :

```ini
session.auto_start = 0  ; Doit être 0 (les sessions sont démarrées par le code)
```

### Problème : "Mot de passe incorrect"

- Vérifiez que la colonne `password` existe dans la table `user`
- Assurez-vous que les mots de passe sont hashés lors de l'inscription
- Pour les utilisateurs existants sans mot de passe, vous devrez les mettre à jour manuellement

### Problème : "Erreur lors de la création du compte"

- Vérifiez que l'email n'est pas déjà utilisé
- Assurez-vous que tous les champs requis sont remplis
- Vérifiez les logs d'erreur PHP pour plus de détails

## 📚 Structure des fichiers

- `app/Controllers/AuthController.php` - Contrôleur d'authentification
- `app/Views/auth/login.php` - Vue de connexion
- `app/Views/auth/register.php` - Vue d'inscription
- `app/Models/User.php` - Modèle utilisateur (mis à jour avec support password)
- `app/Core/Controller.php` - Classe de base (ajout gestion sessions)

## ✅ Test

Pour tester le système :

1. Créez un compte via `/auth/register`
2. Déconnectez-vous
3. Reconnectez-vous avec vos identifiants
4. Ajoutez des produits au panier
5. Passez une commande
6. Consultez vos commandes dans `/orders`










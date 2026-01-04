-- Migration pour ajouter le support de l'authentification
-- Ajoute la colonne password à la table user

-- Ajouter la colonne password si elle n'existe pas déjà
ALTER TABLE user 
ADD COLUMN IF NOT EXISTS password VARCHAR(255) NULL;

-- Mettre à jour les utilisateurs existants avec un mot de passe par défaut (à changer après)
-- UPDATE user SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE password IS NULL;
-- Le mot de passe par défaut est "password" (hash bcrypt)










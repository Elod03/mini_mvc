-- Script SQL manuel pour créer les tables essentielles
-- À exécuter dans phpMyAdmin : http://localhost/phpmyadmin
-- Sélectionnez la base "mini_mvc" puis cliquez sur "SQL" et collez ce script

-- Supprimer les anciennes tables (si elles existent)
DROP TABLE IF EXISTS commande_produit;
DROP TABLE IF EXISTS commande;
DROP TABLE IF EXISTS panier;
DROP TABLE IF EXISTS produit;
DROP TABLE IF EXISTS categorie;
DROP TABLE IF EXISTS user;

-- Créer la table user
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Créer la table categorie
CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Créer la table produit (la plus importante)
CREATE TABLE produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image_url VARCHAR(500),
    categorie_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insérer des produits d'exemple
INSERT INTO produit (nom, description, prix, stock) VALUES
('Ordinateur Portable', 'Ordinateur portable performant', 999.99, 10),
('Smartphone', 'Téléphone intelligent dernière génération', 699.99, 25),
('Casque Audio', 'Casque sans fil haute qualité', 149.99, 50),
('Clavier Mécanique', 'Clavier gaming RGB', 89.99, 30),
('Souris Gaming', 'Souris gaming ergonomique', 59.99, 40);

-- Message de confirmation
SELECT 'Tables créées avec succès !' as status, COUNT(*) as produits FROM produit;

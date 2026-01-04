-- Script SQL de base pour votre boutique de maquillage et soins
-- Crée seulement les tables et les catégories vides
-- À exécuter dans phpMyAdmin : http://localhost/phpmyadmin

-- Supprimer les anciennes tables (si elles existent)
DROP TABLE IF EXISTS commande_produit;
DROP TABLE IF EXISTS commande;
DROP TABLE IF EXISTS panier;
DROP TABLE IF EXISTS produit;
DROP TABLE IF EXISTS categorie;
DROP TABLE IF EXISTS user;

-- ===========================================
-- CRÉATION DES TABLES
-- ===========================================

-- Créer la table user (utilisateurs)
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

-- Créer la table produit
CREATE TABLE produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    stock INT NOT NULL DEFAULT 0,
    image_url VARCHAR(500),
    categorie_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nom (nom),
    INDEX idx_prix (prix),
    INDEX idx_categorie (categorie_id),
    FOREIGN KEY (categorie_id) REFERENCES categorie(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Créer la table panier
CREATE TABLE panier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_product (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES produit(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Créer la table commande
CREATE TABLE commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    statut ENUM('en_attente', 'validee', 'annulee') DEFAULT 'en_attente',
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Créer la table commande_produit
CREATE TABLE commande_produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    product_id INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (commande_id) REFERENCES commande(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES produit(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===========================================
-- CATÉGORIES DE BASE (VIDES)
-- ===========================================

INSERT INTO categorie (nom, description) VALUES
('Maquillage Visage', 'Fond de teint, poudre, blush, highlighter et autres produits pour le visage'),
('Maquillage Yeux', 'Mascara, eyeliner, fard à paupières, crayons et produits pour les yeux'),
('Maquillage Lèvres', 'Rouges à lèvres, gloss, crayons à lèvres et soins pour les lèvres'),
('Soins Visage', 'Crèmes hydratantes, sérums, masques et soins quotidiens du visage'),
('Soins Corps', 'Huiles, laits, gels douche et produits de soin pour le corps'),
('Soins Cheveux', 'Shampoings, après-shampoings, masques et produits capillaires'),
('Parfums', 'Eaux de toilette, parfums et eaux de Cologne'),
('Accessoires', 'Pinceaux, éponges, miroirs et outils de maquillage');

-- ===========================================
-- UTILISATEUR ADMINISTRATEUR
-- ===========================================

INSERT INTO user (nom, email, password) VALUES
('Administrateur', 'admin@votre-boutique.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ===========================================
-- VÉRIFICATION
-- ===========================================

SELECT
    (SELECT COUNT(*) FROM user) as utilisateurs,
    (SELECT COUNT(*) FROM categorie) as categories,
    (SELECT COUNT(*) FROM produit) as produits;

SELECT 'Base de données créée avec succès ! Ajoutez vos produits via le site.' as statut;

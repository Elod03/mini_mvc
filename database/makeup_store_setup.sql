-- Script SQL pour créer la base de données de votre boutique de maquillage et soins
-- À exécuter dans phpMyAdmin : http://localhost/phpmyadmin
-- 1. Créez d'abord la base "mini_mvc" (si elle n'existe pas)
-- 2. Sélectionnez la base "mini_mvc"
-- 3. Cliquez sur "SQL" et collez ce script complet

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
    INDEX idx_categorie (categorie_id)
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
    CONSTRAINT fk_panier_user FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_panier_produit FOREIGN KEY (product_id) REFERENCES produit(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Créer la table commande
CREATE TABLE commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    statut ENUM('en_attente', 'validee', 'annulee') DEFAULT 'en_attente',
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_commande_user FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Créer la table commande_produit
CREATE TABLE commande_produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    product_id INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_commande_produit_commande FOREIGN KEY (commande_id) REFERENCES commande(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_commande_produit_produit FOREIGN KEY (product_id) REFERENCES produit(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===========================================
-- DONNÉES D'EXEMPLE POUR VOTRE BOUTIQUE
-- ===========================================

-- Insérer les catégories de produits de beauté
INSERT INTO categorie (nom, description) VALUES
('Maquillage Visage', 'Fond de teint, poudre, blush, highlighter et autres produits pour le visage'),
('Maquillage Yeux', 'Mascara, eyeliner, fard à paupières, crayons et produits pour les yeux'),
('Maquillage Lèvres', 'Rouges à lèvres, gloss, crayons à lèvres et soins pour les lèvres'),
('Soins Visage', 'Crèmes hydratantes, sérums, masques et soins quotidiens du visage'),
('Soins Corps', 'Huiles, laits, gels douche et produits de soin pour le corps'),
('Soins Cheveux', 'Shampoings, après-shampoings, masques et produits capillaires'),
('Parfums', 'Eaux de toilette, parfums et eaux de Cologne'),
('Accessoires', 'Pinceaux, éponges, miroirs et outils de maquillage');

-- Insérer des produits d'exemple
INSERT INTO produit (nom, description, prix, stock, categorie_id) VALUES
-- Maquillage Visage
('Fond de Teint Matifiant', 'Fond de teint longue tenue avec SPF 15, fini mat naturel pour tous types de peau', 45.90, 25, 1),
('Poudre Compacte Transparente', 'Poudre setting transparente pour fixer le maquillage toute la journée', 18.50, 40, 1),
('Blush Pêche Naturel', 'Fard à joues en poudre, teinte pêche naturelle pour un effet bonne mine', 22.99, 30, 1),
('Highlighter Or Rosé', 'Enlumineur liquide avec fini perlé, effet glow naturel', 28.90, 20, 1),
('Primer Hydratant', 'Base de maquillage hydratante qui prolonge la tenue du fond de teint', 32.50, 35, 1),

-- Maquillage Yeux
('Mascara Volume Noir', 'Mascara volumateur longue tenue, effet regard de biche', 12.99, 60, 2),
('Palette Fards à Paupières Nude', 'Palette de 6 teintes nudes pour un maquillage naturel', 24.90, 25, 2),
('Eyeliner Noir Précision', 'Eyeliner gel noir ultra précis avec applicateur biseauté', 16.50, 45, 2),
('Crayon Khôl Brun', 'Crayon khôl brun doux pour un regard smoky naturel', 14.99, 50, 2),
('Sourcils Poudre Compacte', 'Poudre compacte pour restructurer et colorer les sourcils', 19.90, 40, 2),

-- Maquillage Lèvres
('Rouge à Lèvres Rouge Classique', 'Rouge à lèvres rouge mat longue tenue, teinte intemporelle', 25.90, 30, 3),
('Gloss Hydratant Transparent', 'Gloss transparent ultra hydratant avec effet volume', 15.50, 55, 3),
('Crayon à Lèvres Nude', 'Crayon à lèvres nude naturel pour définir et hydrater', 13.99, 40, 3),
('Bâton à Lèvres Teinté', 'Bâton teinté hydratant pour un effet naturel toute la journée', 18.90, 35, 3),
('Baume à Lèvres Réparateur', 'Baume réparateur avec beurre de karité et SPF 15', 9.99, 70, 3),

-- Soins Visage
('Crème Hydratante Jour', 'Crème hydratante légère SPF 30 pour usage quotidien', 35.90, 40, 4),
('Sérum Vitamine C', 'Sérum anti-âge à la vitamine C pour illuminer la peau', 52.50, 20, 4),
('Masque Purifiant Argile', 'Masque purifiant à l'argile verte pour peau mixte à grasse', 16.99, 45, 4),
('Gel Nettoyant Doux', 'Gel nettoyant sans savon pour peau sensible', 22.90, 50, 4),
('Contouring Yeux', 'Crème contour des yeux anti-poches et anti-cernes', 29.99, 35, 4),

-- Soins Corps
('Huile de Soin Corps Nourrissante', 'Huile sèche nourrissante au monoï et vanille', 39.90, 25, 5),
('Lait Corporel Hydratant', 'Lait corporel hydratant 48h avec aloé vera', 26.50, 40, 5),
('Gel Douche Exfoliant', 'Gel douche exfoliant aux grains de sucre fin', 14.99, 55, 5),
('Crème Mains Réparatrice', 'Crème mains ultra nourrissante au beurre de karité', 12.90, 60, 5),
('Brume d\'Eau Thermale', 'Eau thermale pure en brume pour apaiser la peau', 8.99, 75, 5),

-- Soins Cheveux
('Shampooing Antipelliculaire', 'Shampooing doux antipelliculaire au zinc pyrithione', 11.90, 50, 6),
('Après-Shampooing Hydratant', 'Après-shampooing nourrissant aux huiles essentielles', 13.50, 45, 6),
('Masque Capillaire Réparateur', 'Masque intensif pour cheveux abîmés et secs', 19.99, 30, 6),
('Huile de Soin Cheveux', 'Huile nourrissante multi-usages pour tous types de cheveux', 24.90, 35, 6),
('Spray Anti-Frisottis', 'Spray thermo-protecteur anti-frisottis', 16.50, 40, 6),

-- Parfums
('Eau de Toilette Florale', 'Parfum floral frais avec notes de jasmin et rose', 65.90, 15, 7),
('Parfum Oriental Intense', 'Parfum oriental sensuel avec patchouli et vanille', 89.99, 12, 7),
('Eau de Cologne Citronnée', 'Eau de Cologne fraîche et citronnée pour homme', 42.50, 20, 7),
('Parfum Fruité Doux', 'Parfum fruité sucré avec mangue et fruits exotiques', 55.90, 18, 7),
('Eau de Toilette Boisée', 'Parfum boisé masculin avec cèdre et vétiver', 72.99, 14, 7),

-- Accessoires
('Pinceau Fondation Pro', 'Pinceau pro en synthétique pour appliquer le fond de teint', 15.99, 30, 8),
('Éponge Maquillage Triangle', 'Éponge beauté triangle réutilisable haute qualité', 8.50, 80, 8),
('Palette Pinceaux Complète', 'Set de 10 pinceaux professionnels avec étui', 49.99, 15, 8),
('Miroir Grossissant LED', 'Miroir de maquillage grossissant avec éclairage LED', 34.90, 25, 8),
('Pochette Maquillage', 'Pochette de rangement organisée pour produits de beauté', 22.50, 40, 8);

-- Insérer des utilisateurs de test
INSERT INTO user (nom, email, password) VALUES
('Admin Boutique', 'admin@boutique.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Client Test', 'client@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ===========================================
-- VÉRIFICATION FINALE
-- ===========================================

-- Afficher le résumé des données créées
SELECT
    (SELECT COUNT(*) FROM user) as utilisateurs,
    (SELECT COUNT(*) FROM categorie) as categories,
    (SELECT COUNT(*) FROM produit) as produits,
    (SELECT SUM(stock) FROM produit) as stock_total,
    (SELECT AVG(prix) FROM produit) as prix_moyen;

SELECT 'Base de données créée avec succès ! Votre boutique de beauté est prête !' as statut;

<?php
/**
 * Script complet pour créer la base de données mini_mvc
 * Crée toutes les tables nécessaires au fonctionnement de l'application
 */

echo "🚀 Création complète de la base de données mini_mvc\n";
echo str_repeat("=", 50) . "\n\n";

try {
    // Connexion sans spécifier de base de données
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Créer la base de données si elle n'existe pas
    echo "1. Création de la base de données mini_mvc...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS mini_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Base de données créée/vérifiée\n\n";

    // Sélectionner la base de données
    $pdo->exec("USE mini_mvc");

    // Créer les tables de base
    echo "2. Création des tables de base...\n";

    // Table user
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci
    ");
    echo "✅ Table 'user' créée\n";

    // Table produit
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS produit (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            description TEXT,
            prix DECIMAL(10,2) NOT NULL,
            stock INT NOT NULL DEFAULT 0,
            image_url VARCHAR(500),
            categorie_id INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci
    ");
    echo "✅ Table 'produit' créée\n";

    // Table categorie
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categorie (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(150) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci
    ");
    echo "✅ Table 'categorie' créée\n";

    // Table panier
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS panier (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            product_id INT NOT NULL,
            quantite INT NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_user_product (user_id, product_id),
            CONSTRAINT fk_panier_user FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_panier_produit FOREIGN KEY (product_id) REFERENCES produit(id) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci
    ");
    echo "✅ Table 'panier' créée\n";

    // Table commande
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS commande (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            statut ENUM('en_attente', 'validee', 'annulee') DEFAULT 'en_attente',
            total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_commande_user FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci
    ");
    echo "✅ Table 'commande' créée\n";

    // Table commande_produit
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS commande_produit (
            id INT AUTO_INCREMENT PRIMARY KEY,
            commande_id INT NOT NULL,
            product_id INT NOT NULL,
            quantite INT NOT NULL DEFAULT 1,
            prix_unitaire DECIMAL(10,2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_commande_produit_commande FOREIGN KEY (commande_id) REFERENCES commande(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_commande_produit_produit FOREIGN KEY (product_id) REFERENCES produit(id) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci
    ");
    echo "✅ Table 'commande_produit' créée\n";

    // Ajouter la clé étrangère categorie_id à produit
    try {
        $pdo->exec("
            ALTER TABLE produit
            ADD CONSTRAINT fk_produit_categorie
            FOREIGN KEY (categorie_id) REFERENCES categorie(id)
            ON DELETE SET NULL ON UPDATE CASCADE
        ");
        echo "✅ Clé étrangère categorie_id ajoutée à produit\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "⚠ Clé étrangère categorie_id déjà existante (ignoré)\n";
        } else {
            throw $e;
        }
    }

    // Insérer des données d'exemple
    echo "\n3. Insertion des données d'exemple...\n";

    // Catégories d'exemple
    $pdo->exec("
        INSERT INTO categorie (nom, description) VALUES
        ('Électronique', 'Produits électroniques et gadgets'),
        ('Vêtements', 'Vêtements et accessoires de mode'),
        ('Alimentation', 'Produits alimentaires et boissons'),
        ('Maison & Jardin', 'Articles pour la maison et le jardin'),
        ('Sports & Loisirs', 'Équipements sportifs et loisirs')
        ON DUPLICATE KEY UPDATE nom = VALUES(nom)
    ");
    echo "✅ Catégories d'exemple insérées\n";

    // Produits d'exemple
    $pdo->exec("
        INSERT INTO produit (nom, description, prix, stock, categorie_id) VALUES
        ('iPhone 15', 'Le dernier smartphone Apple avec caméra avancée', 1199.99, 50, 1),
        ('MacBook Pro M3', 'Ordinateur portable professionnel Apple', 2499.99, 25, 1),
        ('T-shirt Nike', 'T-shirt sport confortable et respirant', 29.99, 100, 2),
        ('Jean Levi\'s', 'Jean classique coupe droite', 89.99, 75, 2),
        ('Pain complet', 'Pain bio au levain naturel', 3.50, 200, 3),
        ('Café moulu', 'Café arabica torréfié fraichement', 12.99, 150, 3),
        ('Lampe de bureau LED', 'Lampe design avec variateur d\'intensité', 79.99, 30, 4),
        ('Plante verte', 'Ficus lyrata - Plante d\'intérieur dépolluante', 45.99, 20, 4),
        ('Ballon de football', 'Ballon FIFA approuvé pour matches officiels', 39.99, 40, 5),
        ('Raquette de tennis', 'Raquette Wilson Pro Staff légère', 199.99, 15, 5)
        ON DUPLICATE KEY UPDATE nom = VALUES(nom)
    ");
    echo "✅ Produits d'exemple insérés\n";

    // Utilisateur de test
    $pdo->exec("
        INSERT INTO user (nom, email, password) VALUES
        ('Admin Test', 'admin@test.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
        ON DUPLICATE KEY UPDATE nom = VALUES(nom)
    ");
    echo "✅ Utilisateur de test créé (mot de passe: password)\n";

    // Vérification finale
    echo "\n4. Vérification des tables créées...\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Tables créées dans mini_mvc :\n";
    foreach ($tables as $table) {
        $countStmt = $pdo->prepare("SELECT COUNT(*) as count FROM `$table`");
        $countStmt->execute();
        $count = $countStmt->fetch()['count'];
        echo "  - $table ($count enregistrements)\n";
    }

    echo "\n" . str_repeat("=", 50) . "\n";
    echo "🎉 Base de données mini_mvc créée avec succès !\n";
    echo "📊 Tables créées : " . count($tables) . "\n";
    echo "🚀 Votre application est maintenant prête à fonctionner !\n";
    echo "\nAccédez à : http://localhost/mini_mvc/\n";
    echo str_repeat("=", 50) . "\n";

} catch (PDOException $e) {
    echo "\n❌ Erreur lors de la création de la base de données :\n";
    echo "Message : " . $e->getMessage() . "\n";
    echo "Code : " . $e->getCode() . "\n\n";

    echo "💡 Solutions possibles :\n";
    echo "1. Vérifiez que MySQL est démarré\n";
    echo "2. Vérifiez les droits de l'utilisateur 'root'\n";
    echo "3. Vérifiez que le port 3306 n'est pas bloqué\n";

    exit(1);
}
?>

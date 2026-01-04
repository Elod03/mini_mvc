<?php
/**
 * Script rapide pour créer seulement les tables essentielles
 */

echo "🚀 Création rapide des tables essentielles\n";
echo str_repeat("=", 50) . "\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Créer la base si elle n'existe pas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS mini_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE mini_mvc");

    echo "📦 Création des tables...\n";

    // Table user
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "✅ user\n";

    // Table categorie
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categorie (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(150) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "✅ categorie\n";

    // Table produit (la plus importante)
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "✅ produit\n";

    // Ajouter quelques produits d'exemple
    echo "\n📝 Ajout de produits d'exemple...\n";
    $pdo->exec("
        INSERT INTO produit (nom, description, prix, stock) VALUES
        ('Ordinateur Portable', 'Ordinateur portable performant', 999.99, 10),
        ('Smartphone', 'Téléphone intelligent dernière génération', 699.99, 25),
        ('Casque Audio', 'Casque sans fil haute qualité', 149.99, 50),
        ('Clavier Mécanique', 'Clavier gaming RGB', 89.99, 30),
        ('Souris Gaming', 'Souris gaming ergonomique', 59.99, 40)
        ON DUPLICATE KEY UPDATE nom = VALUES(nom)
    ");
    echo "✅ Produits ajoutés\n";

    // Vérification
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM produit");
    $count = $stmt->fetch()['total'];

    echo "\n" . str_repeat("=", 50) . "\n";
    echo "🎉 Tables créées avec succès !\n";
    echo "📊 Produits dans la base : $count\n";
    echo "🚀 Testez votre application maintenant !\n";
    echo "\nURL : http://localhost/mini_mvc/\n";
    echo str_repeat("=", 50) . "\n";

} catch (Exception $e) {
    echo "\n❌ Erreur :\n";
    echo $e->getMessage() . "\n\n";

    echo "💡 Solutions :\n";
    echo "1. Vérifiez que MySQL est démarré\n";
    echo "2. Vérifiez que XAMPP fonctionne\n";
    echo "3. Réessayez ce script\n";
}
?>

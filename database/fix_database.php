<?php
/**
 * Script pour réparer la base de données mini_mvc
 */

echo "🔧 Réparation de la base mini_mvc\n";
echo str_repeat("=", 50) . "\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // S'assurer que la base existe
    $pdo->exec("CREATE DATABASE IF NOT EXISTS mini_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE mini_mvc");

    echo "🗑️ Suppression des anciennes tables...\n";

    // Supprimer les tables dans le bon ordre (à cause des clés étrangères)
    $tablesToDrop = ['commande_produit', 'commande', 'panier', 'produit', 'categorie', 'user'];

    foreach ($tablesToDrop as $table) {
        try {
            $pdo->exec("DROP TABLE IF EXISTS `$table`");
            echo "✅ $table supprimée\n";
        } catch (Exception $e) {
            echo "⚠️ Impossible de supprimer $table : " . $e->getMessage() . "\n";
        }
    }

    echo "\n🏗️ Recréation des tables...\n";

    // Table user
    $pdo->exec("
        CREATE TABLE user (
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
        CREATE TABLE categorie (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(150) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "✅ categorie\n";

    // Table produit
    $pdo->exec("
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "✅ produit\n";

    // Ajouter des produits d'exemple
    echo "\n📝 Ajout de produits...\n";
    $pdo->exec("
        INSERT INTO produit (nom, description, prix, stock) VALUES
        ('Ordinateur Portable', 'Ordinateur portable performant', 999.99, 10),
        ('Smartphone', 'Téléphone intelligent dernière génération', 699.99, 25),
        ('Casque Audio', 'Casque sans fil haute qualité', 149.99, 50),
        ('Clavier Mécanique', 'Clavier gaming RGB', 89.99, 30),
        ('Souris Gaming', 'Souris gaming ergonomique', 59.99, 40)
    ");
    echo "✅ 5 produits ajoutés\n";

    // Vérification
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM produit");
    $count = $stmt->fetch()['total'];

    echo "\n" . str_repeat("=", 50) . "\n";
    echo "🎉 Base de données réparée !\n";
    echo "📊 Produits : $count\n";
    echo "🚀 Testez maintenant : http://localhost/mini_mvc/\n";
    echo str_repeat("=", 50) . "\n";

} catch (Exception $e) {
    echo "\n❌ Erreur :\n";
    echo $e->getMessage() . "\n\n";

    echo "💡 Solutions :\n";
    echo "1. MySQL doit être démarré\n";
    echo "2. Vérifiez les droits utilisateur\n";
    echo "3. Redémarrez XAMPP\n";
}
?>

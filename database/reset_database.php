<?php
/**
 * Script pour recréer complètement la base de données mini_mvc
 */

echo "🔄 Recréation complète de la base mini_mvc\n";
echo str_repeat("=", 50) . "\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Supprimer la base si elle existe
    echo "🗑️ Suppression de l'ancienne base...\n";
    $pdo->exec("DROP DATABASE IF EXISTS mini_mvc");
    echo "✅ Ancienne base supprimée\n\n";

    // Recréer la base
    echo "🏗️ Création de la nouvelle base...\n";
    $pdo->exec("CREATE DATABASE mini_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE mini_mvc");
    echo "✅ Nouvelle base créée\n\n";

    // Créer les tables
    echo "📦 Création des tables...\n";

    $tables = [
        "user" => "
            CREATE TABLE user (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(100) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ",

        "categorie" => "
            CREATE TABLE categorie (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(150) NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ",

        "produit" => "
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
        "
    ];

    foreach ($tables as $name => $sql) {
        $pdo->exec($sql);
        echo "✅ $name\n";
    }

    // Ajouter quelques produits
    echo "\n📝 Ajout de produits d'exemple...\n";
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
    echo "🎉 Base de données recréée avec succès !\n";
    echo "📊 Produits : $count\n";
    echo "🚀 Testez maintenant : http://localhost/mini_mvc/\n";
    echo str_repeat("=", 50) . "\n";

} catch (Exception $e) {
    echo "\n❌ Erreur :\n";
    echo $e->getMessage() . "\n\n";

    echo "💡 Vérifiez :\n";
    echo "1. MySQL est démarré dans XAMPP\n";
    echo "2. Le port 3306 n'est pas bloqué\n";
    echo "3. Les droits de l'utilisateur root\n";
}
?>

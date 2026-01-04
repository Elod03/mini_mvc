<?php
/**
 * Script de test de la connexion à la base de données
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use Mini\Core\Database;

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Test Connexion DB</title></head><body>";
echo "<h1>🔍 Test de connexion à la base de données</h1>";

try {
    // Test de la connexion
    echo "<h2>Test 1: Connexion de base</h2>";
    $pdo = Database::getPDO();
    echo "✅ Connexion PDO établie<br>";

    // Test d'une requête simple
    echo "<h2>Test 2: Requête simple</h2>";
    $stmt = $pdo->query('SELECT 1 as test, NOW() as current_time');
    $result = $stmt->fetch();
    echo "✅ Requête exécutée avec succès<br>";
    echo "📅 Heure actuelle : " . $result['current_time'] . "<br>";

    // Test de la méthode testConnection
    echo "<h2>Test 3: Test de connexion personnalisé</h2>";
    if (Database::testConnection()) {
        echo "✅ Test de connexion réussi<br>";
    } else {
        echo "❌ Test de connexion échoué<br>";
    }

    // Test de récupération d'une table
    echo "<h2>Test 4: Test des tables</h2>";
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "✅ Tables trouvées : " . count($tables) . "<br>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . htmlspecialchars($table) . "</li>";
    }
    echo "</ul>";

    echo "<hr><h2>🎉 Tous les tests sont passés avec succès !</h2>";

} catch (\Exception $e) {
    echo "<h2>❌ Erreur de connexion</h2>";
    echo "<strong>Message :</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<strong>Code :</strong> " . htmlspecialchars($e->getCode()) . "<br>";
    echo "<strong>Fichier :</strong> " . htmlspecialchars($e->getFile()) . "<br>";
    echo "<strong>Ligne :</strong> " . $e->getLine() . "<br>";

    echo "<h3>💡 Solutions possibles :</h3>";
    echo "<ul>";
    echo "<li>Vérifiez que MySQL/XAMPP est démarré</li>";
    echo "<li>Vérifiez les paramètres dans config.ini</li>";
    echo "<li>Exécutez le script mysql_optimization.sql dans phpMyAdmin</li>";
    echo "<li>Redémarrez Apache et MySQL</li>";
    echo "</ul>";
}

echo "</body></html>";
?>

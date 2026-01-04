<?php
/**
 * Supprime la colonne date devenue inutile
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use Mini\Core\Database;

try {
    $pdo = Database::getPDO();
    
    // Vérifie si la colonne date existe
    $stmt = $pdo->query("SHOW COLUMNS FROM commande LIKE 'date'");
    if ($stmt->rowCount() > 0) {
        echo "Suppression de la colonne date...\n";
        $pdo->exec("ALTER TABLE commande DROP COLUMN date");
        echo "✅ Colonne date supprimée.\n";
    } else {
        echo "✅ La colonne date n'existe pas (déjà supprimée).\n";
    }
    
} catch (Exception $e) {
    echo "⚠️ Erreur: " . $e->getMessage() . "\n";
}








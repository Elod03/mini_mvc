<?php
/**
 * Script pour exécuter la migration de la base de données
 * Crée les tables nécessaires : categorie, panier, commande, commande_produit
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use Mini\Core\Database;

try {
    $pdo = Database::getPDO();
    
    // Lire le fichier de migration
    $migrationFile = __DIR__ . '/migrations.sql';
    
    if (!file_exists($migrationFile)) {
        die("Erreur : Le fichier migrations.sql n'existe pas dans le dossier database/\n");
    }
    
    $sql = file_get_contents($migrationFile);
    
    // Supprimer les commentaires SQL (-- ...)
    $sql = preg_replace('/--.*$/m', '', $sql);
    
    // Diviser le SQL en requêtes individuelles (séparées par ;)
    $statements = explode(';', $sql);
    
    echo "Début de l'exécution de la migration...\n\n";
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($statements as $statement) {
        // Nettoyer la requête
        $statement = trim($statement);
        
        // Ignorer les lignes vides
        if (empty($statement)) {
            continue;
        }
        
        try {
            $pdo->exec($statement);
            $successCount++;
            
            // Afficher un message informatif selon le type de requête
            if (preg_match('/CREATE TABLE.*?IF NOT EXISTS\s+(\w+)/i', $statement, $matches)) {
                echo "✓ Table '{$matches[1]}' créée/vérifiée avec succès\n";
            } elseif (preg_match('/CREATE TABLE\s+(\w+)/i', $statement, $matches)) {
                echo "✓ Table '{$matches[1]}' créée avec succès\n";
            } elseif (preg_match('/ALTER TABLE\s+(\w+)/i', $statement, $matches)) {
                echo "✓ Table '{$matches[1]}' modifiée avec succès\n";
            } elseif (preg_match('/INSERT INTO\s+(\w+)/i', $statement, $matches)) {
                echo "✓ Données insérées dans '{$matches[1]}'\n";
            }
        } catch (PDOException $e) {
            $errorCount++;
            // Ignorer les erreurs de contrainte/clé déjà existante
            $errorMsg = $e->getMessage();
            if (strpos($errorMsg, 'Duplicate key name') !== false || 
                strpos($errorMsg, 'already exists') !== false ||
                strpos($errorMsg, 'Duplicate column name') !== false ||
                strpos($errorMsg, 'Duplicate entry') !== false ||
                strpos($errorMsg, 'Cannot add foreign key constraint') !== false) {
                echo "⚠ " . $errorMsg . " (ignoré - déjà existant)\n";
            } else {
                echo "✗ Erreur : " . $errorMsg . "\n";
                echo "  Requête : " . substr($statement, 0, 150) . "...\n";
            }
        }
    }
    
    echo "\n=== Résumé ===\n";
    echo "Requêtes exécutées avec succès : $successCount\n";
    echo "Erreurs (ignorées si déjà existant) : $errorCount\n";
    echo "\nMigration terminée !\n";
    
    // Vérifier que la table panier existe maintenant
    $stmt = $pdo->query("SHOW TABLES LIKE 'panier'");
    if ($stmt->rowCount() > 0) {
        echo "\n✓ La table 'panier' existe maintenant dans la base de données.\n";
    } else {
        echo "\n⚠ Attention : La table 'panier' n'a pas été créée. Vérifiez les erreurs ci-dessus.\n";
    }
    
} catch (Exception $e) {
    echo "Erreur fatale : " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}


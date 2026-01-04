<?php
/**
 * Script pour normaliser complètement la table commande
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use Mini\Core\Database;

try {
    $pdo = Database::getPDO();
    
    echo "=== Normalisation de la table commande ===\n\n";
    
    // Vérifie la structure actuelle
    $stmt = $pdo->query("DESCRIBE commande");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'Field');
    
    echo "Colonnes actuelles: " . implode(', ', $columnNames) . "\n\n";
    
    // 1. Supprime utilisateur_id si user_id existe (on garde user_id)
    if (in_array('utilisateur_id', $columnNames) && in_array('user_id', $columnNames)) {
        echo "⚠️ Les deux colonnes utilisateur_id et user_id existent.\n";
        echo "Vérification des données...\n";
        
        // Vérifie si utilisateur_id a des valeurs différentes de user_id
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM commande WHERE utilisateur_id != user_id OR (utilisateur_id IS NOT NULL AND user_id IS NULL)");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            echo "⚠️ Il y a des différences entre utilisateur_id et user_id. Migration des données...\n";
            $pdo->exec("UPDATE commande SET user_id = utilisateur_id WHERE user_id IS NULL OR user_id = 0");
        }
        
        echo "Suppression de la colonne utilisateur_id...\n";
        try {
            // Supprime d'abord la clé étrangère si elle existe
            $pdo->exec("ALTER TABLE commande DROP FOREIGN KEY IF EXISTS fk_commande_utilisateur");
            $pdo->exec("ALTER TABLE commande DROP COLUMN utilisateur_id");
            echo "✅ Colonne utilisateur_id supprimée.\n";
        } catch (PDOException $e) {
            echo "⚠️ Erreur lors de la suppression: " . $e->getMessage() . "\n";
        }
    }
    
    // 2. Ajoute created_at si elle n'existe pas
    if (!in_array('created_at', $columnNames)) {
        echo "Ajout de la colonne created_at...\n";
        try {
            if (in_array('date', $columnNames)) {
                // Si date existe, on la convertit en created_at
                $pdo->exec("ALTER TABLE commande ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER total");
                $pdo->exec("UPDATE commande SET created_at = date WHERE date IS NOT NULL");
                echo "✅ Colonne created_at ajoutée et données migrées depuis date.\n";
            } else {
                $pdo->exec("ALTER TABLE commande ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER total");
                echo "✅ Colonne created_at ajoutée.\n";
            }
        } catch (PDOException $e) {
            echo "⚠️ Erreur: " . $e->getMessage() . "\n";
        }
    }
    
    // 3. Ajoute updated_at si elle n'existe pas
    if (!in_array('updated_at', $columnNames)) {
        echo "Ajout de la colonne updated_at...\n";
        try {
            $pdo->exec("ALTER TABLE commande ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at");
            echo "✅ Colonne updated_at ajoutée.\n";
        } catch (PDOException $e) {
            echo "⚠️ Erreur: " . $e->getMessage() . "\n";
        }
    }
    
    // 4. Modifie le type de statut en ENUM si c'est VARCHAR
    $stmt = $pdo->query("SHOW COLUMNS FROM commande WHERE Field = 'statut'");
    $statutColumn = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($statutColumn && strpos($statutColumn['Type'], 'varchar') !== false) {
        echo "Modification du type de statut en ENUM...\n";
        try {
            // Convertit les valeurs existantes si nécessaire
            $pdo->exec("UPDATE commande SET statut = 'en_attente' WHERE statut NOT IN ('en_attente', 'validee', 'annulee')");
            $pdo->exec("ALTER TABLE commande MODIFY COLUMN statut ENUM('en_attente', 'validee', 'annulee') DEFAULT 'en_attente'");
            echo "✅ Type de statut modifié en ENUM.\n";
        } catch (PDOException $e) {
            echo "⚠️ Erreur: " . $e->getMessage() . "\n";
        }
    }
    
    // 5. Vérifie que user_id a la clé étrangère
    $stmt = $pdo->query("
        SELECT CONSTRAINT_NAME 
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'commande' 
        AND COLUMN_NAME = 'user_id' 
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    if ($stmt->rowCount() === 0) {
        echo "Ajout de la clé étrangère pour user_id...\n";
        try {
            $pdo->exec("
                ALTER TABLE commande 
                ADD CONSTRAINT fk_commande_user 
                    FOREIGN KEY (user_id) 
                    REFERENCES user(id) 
                    ON DELETE CASCADE 
                    ON UPDATE CASCADE
            ");
            echo "✅ Clé étrangère ajoutée.\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate key name') === false) {
                echo "⚠️ Erreur: " . $e->getMessage() . "\n";
            } else {
                echo "⚠️ La clé étrangère existe déjà.\n";
            }
        }
    }
    
    // Affiche la structure finale
    echo "\n=== Structure finale ===\n";
    $stmt = $pdo->query("DESCRIBE commande");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        echo sprintf(
            "  %s: %s %s %s %s\n",
            $column['Field'],
            $column['Type'],
            $column['Null'] === 'YES' ? 'NULL' : 'NOT NULL',
            $column['Key'] ? "({$column['Key']})" : '',
            $column['Default'] !== null ? "DEFAULT {$column['Default']}" : ''
        );
    }
    
    echo "\n✅ Normalisation terminée !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}








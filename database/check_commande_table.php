<?php
/**
 * Script pour vérifier et corriger la structure de la table commande
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use Mini\Core\Database;

try {
    $pdo = Database::getPDO();
    
    echo "=== Vérification de la table commande ===\n\n";
    
    // Vérifie si la table existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'commande'");
    if ($stmt->rowCount() === 0) {
        echo "❌ La table 'commande' n'existe pas.\n";
        echo "Création de la table...\n";
        
        $pdo->exec("
            CREATE TABLE commande (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                statut ENUM('en_attente', 'validee', 'annulee') DEFAULT 'en_attente',
                total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_commande_user 
                    FOREIGN KEY (user_id) 
                    REFERENCES user(id) 
                    ON DELETE CASCADE 
                    ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        
        echo "✅ Table 'commande' créée avec succès.\n\n";
    } else {
        echo "✅ La table 'commande' existe.\n\n";
    }
    
    // Vérifie la structure de la table
    echo "=== Structure de la table commande ===\n";
    $stmt = $pdo->query("DESCRIBE commande");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $columnNames = array_column($columns, 'Field');
    echo "Colonnes trouvées: " . implode(', ', $columnNames) . "\n\n";
    
    // Vérifie si user_id existe
    if (!in_array('user_id', $columnNames)) {
        echo "❌ La colonne 'user_id' n'existe pas.\n";
        echo "Ajout de la colonne...\n";
        
        try {
            $pdo->exec("ALTER TABLE commande ADD COLUMN user_id INT NOT NULL AFTER id");
            echo "✅ Colonne 'user_id' ajoutée.\n";
            
            // Ajoute la clé étrangère si elle n'existe pas
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
                    echo "⚠️ Erreur lors de l'ajout de la clé étrangère: " . $e->getMessage() . "\n";
                } else {
                    echo "⚠️ La clé étrangère existe déjà.\n";
                }
            }
        } catch (PDOException $e) {
            echo "❌ Erreur lors de l'ajout de la colonne: " . $e->getMessage() . "\n";
        }
    } else {
        echo "✅ La colonne 'user_id' existe.\n";
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
    
    echo "\n✅ Vérification terminée !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}








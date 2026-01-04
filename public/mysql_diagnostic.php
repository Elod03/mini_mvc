<?php
/**
 * Diagnostic complet MySQL
 */

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Diagnostic MySQL Complet</title>";
echo "<style>
body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;}
.container{max-width:800px;margin:0 auto;background:white;padding:20px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);}
h1{color:#2c3e50;margin-bottom:30px;}
h2{color:#34495e;border-bottom:2px solid #3498db;padding-bottom:5px;margin-top:30px;}
.test-result{margin:10px 0;padding:10px;border-radius:4px;}
.success{background:#d4edda;color:#155724;border:1px solid #c3e6cb;}
.error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;}
.warning{background:#fff3cd;color:#856404;border:1px solid #ffeaa7;}
.info{background:#d1ecf1;color:#0c5460;border:1px solid #bee5eb;}
code{background:#f8f9fa;padding:2px 4px;border-radius:3px;font-family:monospace;}
.btn{display:inline-block;padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:4px;margin:10px 5px 10px 0;}
.btn:hover{background:#0056b3;}
.btn-success{background:#28a745;}
.btn-success:hover{background:#218838;}
</style>";
echo "</head><body>";

echo "<div class='container'>";
echo "<h1>🔍 Diagnostic Complet MySQL</h1>";
echo "<p><strong>Date et heure:</strong> " . date('d/m/Y H:i:s') . "</p>";

// Test 1: Configuration PHP
echo "<h2>1. Configuration PHP</h2>";
echo "<div class='test-result info'>";
echo "<strong>Extension PDO MySQL:</strong> " . (extension_loaded('pdo_mysql') ? '✅ Chargée' : '❌ Non chargée') . "<br>";
echo "<strong>Extension MySQLi:</strong> " . (extension_loaded('mysqli') ? '✅ Chargée' : '❌ Non chargée') . "<br>";
echo "<strong>Version PHP:</strong> " . PHP_VERSION . "<br>";
echo "</div>";

// Test 2: Test de connexion socket
echo "<h2>2. Test de connexion réseau</h2>";
$fp = @fsockopen('127.0.0.1', 3306, $errno, $errstr, 10);
if ($fp) {
    echo "<div class='test-result success'>✅ Connexion au port 3306 réussie</div>";
    fclose($fp);
} else {
    echo "<div class='test-result error'>";
    echo "❌ Impossible de se connecter au port 3306<br>";
    echo "<strong>Erreur:</strong> $errstr (code: $errno)<br>";
    echo "<strong>Cause possible:</strong> MySQL n'est pas démarré";
    echo "</div>";
}

// Test 3: Test de connexion MySQL avec différents paramètres
echo "<h2>3. Test de connexion MySQL</h2>";

$configs = [
    ['host' => '127.0.0.1', 'port' => 3306, 'desc' => 'IPv4 localhost'],
    ['host' => 'localhost', 'port' => 3306, 'desc' => 'Hostname localhost'],
    ['host' => '127.0.0.1', 'port' => 3307, 'desc' => 'Port alternatif 3307'],
    ['host' => '127.0.0.1', 'port' => 3308, 'desc' => 'Port alternatif 3308'],
];

foreach ($configs as $config) {
    try {
        $dsn = "mysql:host={$config['host']};port={$config['port']};charset=utf8";
        $pdo = new PDO($dsn, 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5
        ]);

        echo "<div class='test-result success'>";
        echo "✅ Connexion réussie sur {$config['host']}:{$config['port']} ({$config['desc']})<br>";

        // Test des bases de données
        $stmt = $pdo->query("SHOW DATABASES");
        $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $hasMiniMvc = in_array('mini_mvc', $databases);
        echo "<strong>Base mini_mvc:</strong> " . ($hasMiniMvc ? '✅ Existe' : '❌ N\'existe pas') . "<br>";

        if ($hasMiniMvc) {
            $pdo->exec("USE mini_mvc");
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "<strong>Tables dans mini_mvc:</strong> " . count($tables) . " trouvées<br>";
        }

        break; // Sortir de la boucle si connexion réussie

    } catch (PDOException $e) {
        echo "<div class='test-result error'>";
        echo "❌ Échec sur {$config['host']}:{$config['port']} ({$config['desc']})<br>";
        echo "<strong>Erreur:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
        echo "</div>";
    }
}

// Test 4: État des services
echo "<h2>4. État des services MySQL</h2>";
echo "<div class='test-result info'>";

$services = ['mysql', 'mariadb'];
$serviceFound = false;

foreach ($services as $service) {
    $output = shell_exec("sc query \"$service\" 2>nul");
    if ($output) {
        if (strpos($output, 'RUNNING') !== false) {
            echo "✅ Service $service: RUNNING<br>";
            $serviceFound = true;
        } elseif (strpos($output, 'STOPPED') !== false) {
            echo "⚠️ Service $service: STOPPED<br>";
            $serviceFound = true;
        }
    }
}

if (!$serviceFound) {
    echo "❌ Aucun service MySQL trouvé dans les services Windows<br>";
}

echo "</div>";

// Test 5: Recherche de processus MySQL
echo "<h2>5. Processus MySQL en cours</h2>";
$output = shell_exec("tasklist /FI \"IMAGENAME eq mysqld.exe\" 2>nul");
if (strpos($output, 'mysqld.exe') !== false) {
    echo "<div class='test-result success'>✅ Processus mysqld.exe trouvé en cours d'exécution</div>";
} else {
    echo "<div class='test-result error'>❌ Aucun processus mysqld.exe trouvé</div>";
}

// Test 6: Recherche XAMPP
echo "<h2>6. Recherche XAMPP</h2>";
$xamppPaths = [
    'C:\xampp',
    'C:\Program Files\xampp',
    'D:\xampp',
];

$xamppFound = false;
foreach ($xamppPaths as $path) {
    if (is_dir($path)) {
        echo "<div class='test-result success'>✅ XAMPP trouvé dans: $path</div>";
        $xamppFound = true;

        // Vérifier les fichiers MySQL
        $mysqlPath = $path . '\mysql\bin\mysqld.exe';
        if (file_exists($mysqlPath)) {
            echo "<div class='test-result success'>✅ mysqld.exe trouvé: $mysqlPath</div>";
        } else {
            echo "<div class='test-result warning'>⚠️ mysqld.exe non trouvé dans $mysqlPath</div>";
        }
        break;
    }
}

if (!$xamppFound) {
    echo "<div class='test-result error'>❌ XAMPP non trouvé dans les emplacements standards</div>";
}

// Solutions
echo "<h2>💡 Solutions recommandées</h2>";
echo "<div class='test-result info'>";

echo "<h3>Étape 1: Démarrer MySQL via XAMPP</h3>";
echo "<ol>";
echo "<li>Ouvrez le <strong>XAMPP Control Panel</strong></li>";
echo "<li>Cliquez sur <strong>'Start'</strong> à côté de <strong>'MySQL'</strong></li>";
echo "<li>Attendez que le statut devienne <strong>'Running'</strong> (vert)</li>";
echo "<li>Actualisez cette page pour vérifier</li>";
echo "</ol>";

echo "<h3>Étape 2: Si ça ne marche pas</h3>";
echo "<ol>";
echo "<li>Redémarrez votre ordinateur</li>";
echo "<li>Relancez XAMPP en mode <strong>Administrateur</strong></li>";
echo "<li>Vérifiez que le port 3306 n'est pas utilisé par un autre programme</li>";
echo "</ol>";

echo "<h3>Étape 3: Créer la base de données</h3>";
echo "<ol>";
echo "<li>Allez sur <a href='http://localhost/phpmyadmin' target='_blank'>phpMyAdmin</a></li>";
echo "<li>Créez une base nommée <code>mini_mvc</code></li>";
echo "<li>Importez le fichier <code>database/migrations.sql</code></li>";
echo "</ol>";

echo "</div>";

// Boutons d'action
echo "<h2>🔧 Actions</h2>";
echo "<a href='mysql_diagnostic.php' class='btn'>🔄 Actualiser le diagnostic</a>";
echo "<a href='test_connection.php' class='btn btn-success'>🧪 Tester la connexion DB</a>";
echo "<a href='../' class='btn'>🏠 Retour à l'accueil</a>";

echo "</div>";
echo "</body></html>";
?>

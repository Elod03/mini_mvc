<?php
/**
 * Script de diagnostic MySQL
 */

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Diagnostic MySQL</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} .info{color:blue;}</style>";
echo "</head><body>";

echo "<h1>🔍 Diagnostic MySQL</h1>";
echo "<p><strong>Date du test :</strong> " . date('Y-m-d H:i:s') . "</p>";

// Test 1: Vérifier si le port MySQL est ouvert
echo "<h2>Test 1: Port MySQL (3306)</h2>";
$connection = @fsockopen('127.0.0.1', 3306, $errno, $errstr, 5);
if ($connection) {
    echo "<span class='success'>✅ Port 3306 ouvert et accessible</span><br>";
    fclose($connection);
} else {
    echo "<span class='error'>❌ Port 3306 fermé ou inaccessible</span><br>";
    echo "<strong>Détails:</strong> $errstr ($errno)<br>";
}

// Test 2: Vérifier les services Windows
echo "<h2>Test 2: Services MySQL</h2>";
$services = [
    'mysql' => 'MySQL',
    'mariadb' => 'MariaDB'
];

$foundService = false;
foreach ($services as $serviceName => $displayName) {
    $output = shell_exec("sc query \"$serviceName\" 2>nul");
    if (strpos($output, 'RUNNING') !== false) {
        echo "<span class='success'>✅ Service $displayName en cours d'exécution</span><br>";
        $foundService = true;
        break;
    } elseif (strpos($output, 'STOPPED') !== false) {
        echo "<span class='warning'>⚠️ Service $displayName arrêté</span><br>";
        $foundService = true;
        break;
    }
}

if (!$foundService) {
    echo "<span class='error'>❌ Aucun service MySQL trouvé</span><br>";
}

// Test 3: Tenter une connexion MySQL
echo "<h2>Test 3: Connexion MySQL</h2>";
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5
    ]);
    echo "<span class='success'>✅ Connexion MySQL réussie</span><br>";

    // Test de la base de données
    $stmt = $pdo->query("SHOW DATABASES LIKE 'mini_mvc'");
    if ($stmt->rowCount() > 0) {
        echo "<span class='success'>✅ Base de données 'mini_mvc' existe</span><br>";
    } else {
        echo "<span class='warning'>⚠️ Base de données 'mini_mvc' n'existe pas</span><br>";
    }

} catch (PDOException $e) {
    echo "<span class='error'>❌ Échec de connexion MySQL</span><br>";
    echo "<strong>Erreur:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<strong>Code:</strong> " . $e->getCode() . "<br>";
}

// Test 4: Configuration XAMPP
echo "<h2>Test 4: Configuration XAMPP</h2>";
$xamppPaths = [
    'C:\xampp\mysql\bin\mysqld.exe',
    'C:\xampp\mysql\bin\mysql.exe',
    'C:\Program Files\xampp\mysql\bin\mysqld.exe'
];

$xamppFound = false;
foreach ($xamppPaths as $path) {
    if (file_exists($path)) {
        echo "<span class='success'>✅ XAMPP trouvé : " . dirname(dirname($path)) . "</span><br>";
        $xamppFound = true;
        break;
    }
}

if (!$xamppFound) {
    echo "<span class='warning'>⚠️ XAMPP non trouvé dans les emplacements standards</span><br>";
}

// Test 5: Variables d'environnement
echo "<h2>Test 5: Variables d'environnement</h2>";
$path = getenv('PATH');
$mysqlInPath = false;
if (strpos($path, 'mysql') !== false || strpos($path, 'xampp') !== false) {
    echo "<span class='success'>✅ MySQL dans le PATH</span><br>";
    $mysqlInPath = true;
} else {
    echo "<span class='warning'>⚠️ MySQL probablement pas dans le PATH</span><br>";
}

// Recommandations
echo "<h2>💡 Recommandations</h2>";
echo "<ol>";
echo "<li><strong>Démarrez XAMPP :</strong> Ouvrez XAMPP Control Panel et cliquez sur 'Start' pour Apache et MySQL</li>";
echo "<li><strong>Vérifiez les services :</strong> Dans XAMPP, allez dans 'Services' et assurez-vous que MySQL est démarré</li>";
echo "<li><strong>Redémarrez :</strong> Fermez et rouvrez XAMPP Control Panel</li>";
echo "<li><strong>Firewall :</strong> Désactivez temporairement le firewall Windows pour tester</li>";
echo "<li><strong>Port :</strong> Vérifiez qu'aucun autre programme n'utilise le port 3306</li>";
echo "</ol>";

echo "<hr>";
echo "<p><a href='../public/test_connection.php'>🔗 Tester la connexion à la base de données</a></p>";
echo "<p><a href='/'>🏠 Retour à l'accueil</a></p>";

echo "</body></html>";
?>

<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Mini\Core\Router;
use Mini\Controllers\CartController;

echo "<h1>Test Cart</h1>";

echo "<h2>Test 1: Vérification de la classe</h2>";
if (class_exists('Mini\\Controllers\\CartController')) {
    echo "✅ CartController existe<br>";
} else {
    echo "❌ CartController N'EXISTE PAS<br>";
    exit;
}

echo "<h2>Test 2: Vérification de la méthode</h2>";
if (method_exists('Mini\\Controllers\\CartController', 'show')) {
    echo "✅ La méthode show() existe<br>";
} else {
    echo "❌ La méthode show() N'EXISTE PAS<br>";
    exit;
}

// Test 3: Tester l'instanciation
echo "<h2>Test 3: Instanciation</h2>";
try {
    $controller = new CartController();
    echo "✅ Le contrôleur a été instancié avec succès<br>";
} catch (Exception $e) {
    echo "❌ Erreur lors de l'instanciation: " . $e->getMessage() . "<br>";
    exit;
}

// Test 4: Afficher les routes
echo "<h2>Test 4: Routes définies</h2>";
$routes = [
    ['GET', '/', ['Mini\\Controllers\\HomeController', 'index']],
    ['GET', '/cart', ['Mini\\Controllers\\CartController', 'show']],
    ['POST', '/cart/add', ['Mini\\Controllers\\CartController', 'add']],
];

echo "<pre>";
foreach ($routes as $route) {
    echo "[" . $route[0] . "] " . $route[1] . " -> " . $route[2][0] . "::" . $route[2][1] . "\n";
}
echo "</pre>";

// Test 5: Tester le routeur
echo "<h2>Test 5: Test du routeur</h2>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NON DÉFINI') . "<br>";
echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'NON DÉFINI') . "<br>";

$router = new Router($routes);
echo "✅ Routeur créé<br>";

// Test 6: Simuler une requête GET /cart
echo "<h2>Test 6: Simulation GET /cart</h2>";
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/cart';

echo "Tentative de dispatch...<br>";
try {
    $router->dispatch('GET', '/cart');
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}








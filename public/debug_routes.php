<?php

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Debug Routes</title></head><body>";
echo "<h1>🔍 Debug Routes</h1>";
echo "<pre style='background: #f5f5f5; padding: 20px; border-radius: 5px;'>";

echo "=== Informations serveur ===\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NON DÉFINI') . "\n";
echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'NON DÉFINI') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NON DÉFINI') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'NON DÉFINI') . "\n";
echo "PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? 'NON DÉFINI') . "\n";

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH) ?? '/';

echo "\n=== Traitement du chemin ===\n";
echo "URI parsée: " . $uri . "\n";
echo "Chemin extrait (parse_url): " . $path . "\n";

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$basePath = dirname($scriptName);
if ($basePath === '/' || $basePath === '\\' || $basePath === '.') {
    $basePath = '';
}

echo "Chemin de base (dirname SCRIPT_NAME): '" . $basePath . "'\n";

// Si le chemin commence par le chemin de base, le retirer
$finalPath = $path;
if ($basePath !== '' && strpos($path, $basePath) === 0) {
    $finalPath = substr($path, strlen($basePath));
    if ($finalPath === '') {
        $finalPath = '/';
    }
    echo "Chemin relatif (après retrait du base): " . $finalPath . "\n";
} else {
    echo "Le chemin ne commence pas par le chemin de base\n";
}

// Normaliser
$finalPath = '/' . trim($finalPath, '/');
if ($finalPath !== '/') {
    $finalPath = rtrim($finalPath, '/');
}
echo "Chemin final normalisé: " . $finalPath . "\n";

echo "\n=== Routes définies ===\n";
require dirname(__DIR__) . '/vendor/autoload.php';

$routes = [
    ['GET', '/', ['Home', 'index']],
    ['GET', '/cart', ['Cart', 'show']],
    ['GET', '/products', ['Product', 'list']],
    ['GET', '/auth/login', ['Auth', 'login']],
];

foreach ($routes as [$method, $routePath, $handler]) {
    $match = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === $method && $finalPath === $routePath;
    echo ($match ? '✅' : '  ') . " [$method] $routePath\n";
}

echo "\n=== Test de correspondance ===\n";
echo "Méthode actuelle: " . ($_SERVER['REQUEST_METHOD'] ?? 'GET') . "\n";
echo "Chemin recherché: " . $finalPath . "\n";

echo "</pre>";
echo "<p><a href='/cart'>Tester /cart</a> | <a href='/products'>Tester /products</a> | <a href='/'>Tester /</a></p>";
echo "</body></html>";
?>


<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Mini\Controllers\CartController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo "<h1>⚠️ Vous n'êtes pas connecté</h1>";
    echo "<p>Pour tester le panier, vous devez être connecté.</p>";
    echo "<p><a href='/auth/login'>Se connecter</a></p>";
    echo "<hr>";
    echo "<p><strong>Test avec user_id = 1 (simulation):</strong></p>";
    $_SESSION['user_id'] = 1;
    $_SESSION['user_nom'] = 'Test User';
}

try {
    $controller = new CartController();
    $controller->show();
} catch (Exception $e) {
    echo "<h1>❌ Erreur</h1>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}








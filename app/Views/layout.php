<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? htmlspecialchars($title) : 'App' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$isHome = ($currentPath === '/');
$isProducts = ($currentPath === '/products' || strpos($currentPath, '/products/show') === 0);
$isProductsCreate = ($currentPath === '/products/create');
$isUsersCreate = ($currentPath === '/users/create');
$isCart = ($currentPath === '/cart');
$isOrders = (strpos($currentPath, '/orders') === 0);
$isAuth = (strpos($currentPath, '/auth') === 0);

$user_id = $_SESSION['user_id'] ?? null;
$user_nom = $_SESSION['user_nom'] ?? null;
$isAuthenticated = isset($_SESSION['user_id']);
?>
<header>
    <div class="container">
        <h1>
            <a href="/">🛍️ Mini MVC</a>
        </h1>

        <nav>
            <ul>
                <li>
                    <a href="/" class="<?= $isHome ? 'active' : '' ?>">
                        🏠 Accueil
                    </a>
                </li>
                <li>
                    <a href="/products" class="<?= $isProducts ? 'active' : '' ?>">
                        📦 Produits
                    </a>
                </li>
                <li>
                    <a href="/products/create" class="<?= $isProductsCreate ? 'active' : '' ?>">
                        ➕ Ajouter un produit
                    </a>
                </li>
                <?php if ($isAuthenticated): ?>
                    <li>
                        <a href="/cart" class="<?= $isCart ? 'active' : '' ?>">
                            🛒 Panier
                        </a>
                    </li>
                    <li>
                        <a href="/orders" class="<?= $isOrders ? 'active' : '' ?>">
                            📋 Mes commandes
                        </a>
                    </li>
                    <li>
                        <span class="user-info">
                            👤 <?= htmlspecialchars($user_nom) ?>
                        </span>
                    </li>
                    <li>
                        <a href="/auth/logout" class="btn-danger">
                            🚪 Déconnexion
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="/auth/login" class="<?= ($isAuth && strpos($currentPath, '/auth/login') === 0) ? 'active' : '' ?>">
                            🔐 Connexion
                        </a>
                    </li>
                    <li>
                        <a href="/auth/register" class="btn-success">
                            ✨ Inscription
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
<main>
    <?= $content ?>

</main>
</body>
</html>


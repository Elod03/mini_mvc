<?php
/**
 * Interface web pour créer la base de données
 */

$message = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_database'])) {
    $status = 'running';

    // Capturer la sortie du script
    ob_start();
    include __DIR__ . '/../database/create_database.php';
    $output = ob_get_clean();

    if (strpos($output, '🎉 Base de données mini_mvc créée avec succès') !== false) {
        $status = 'success';
        $message = 'Base de données créée avec succès !';
    } else {
        $status = 'error';
        $message = 'Erreur lors de la création de la base de données.';
    }
}

// Vérifier l'état actuel de la base
$databaseExists = false;
$tablesCount = 0;

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5
    ]);

    $stmt = $pdo->query("SHOW DATABASES LIKE 'mini_mvc'");
    $databaseExists = $stmt->rowCount() > 0;

    if ($databaseExists) {
        $pdo->exec("USE mini_mvc");
        $stmt = $pdo->query("SHOW TABLES");
        $tablesCount = $stmt->rowCount();
    }

} catch (Exception $e) {
    $connectionError = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration de la base de données - Mini MVC</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 600px;
            width: 90%;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
        }
        .status {
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .status.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .status.warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .status.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .db-status {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #dee2e6;
        }
        .db-status h3 {
            margin-top: 0;
            color: #495057;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin: 10px 5px;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        .btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .steps {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .steps ol {
            margin: 0;
            padding-left: 20px;
        }
        .steps li {
            margin: 10px 0;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛠️ Configuration Base de Données</h1>

        <?php if ($status === 'success'): ?>
            <div class="status success">
                ✅ <?php echo htmlspecialchars($message); ?>
            </div>
        <?php elseif ($status === 'error'): ?>
            <div class="status error">
                ❌ <?php echo htmlspecialchars($message); ?>
            </div>
        <?php elseif (isset($connectionError)): ?>
            <div class="status error">
                ❌ Connexion MySQL impossible : <?php echo htmlspecialchars($connectionError); ?>
                <br><br>
                <strong>💡 Solutions :</strong>
                <ul>
                    <li>Démarrez XAMPP Control Panel</li>
                    <li>Cliquez sur "Start" pour MySQL</li>
                    <li>Actualisez cette page</li>
                </ul>
            </div>
        <?php endif; ?>

        <div class="db-status">
            <h3>📊 État actuel de la base de données</h3>
            <p><strong>MySQL :</strong>
                <?php if (isset($connectionError)): ?>
                    ❌ Non connecté
                <?php else: ?>
                    ✅ Connecté
                <?php endif; ?>
            </p>
            <p><strong>Base mini_mvc :</strong>
                <?php if ($databaseExists): ?>
                    ✅ Existe (<?php echo $tablesCount; ?> tables)
                <?php else: ?>
                    ❌ N'existe pas
                <?php endif; ?>
            </p>
        </div>

        <?php if (!isset($connectionError)): ?>
            <form method="post">
                <button type="submit" name="create_database" class="btn btn-success"
                        <?php echo ($status === 'running') ? 'disabled' : ''; ?>>
                    <?php if ($status === 'running'): ?>
                        <span class="loading"></span>Création en cours...
                    <?php else: ?>
                        🚀 Créer la base de données
                    <?php endif; ?>
                </button>
            </form>
        <?php endif; ?>

        <div class="steps">
            <h3>📋 Ce que fait ce script :</h3>
            <ol>
                <li>Crée la base de données <code>mini_mvc</code></li>
                <li>Crée toutes les tables nécessaires (user, produit, categorie, panier, commande, etc.)</li>
                <li>Ajoute les contraintes de clés étrangères</li>
                <li>Insère des données d'exemple (catégories et produits)</li>
                <li>Crée un utilisateur de test (admin@test.com / password)</li>
            </ol>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="../" class="btn">🏠 Retour à l'accueil</a>
            <a href="test_connection.php" class="btn">🧪 Tester la connexion</a>
            <a href="mysql_diagnostic.php" class="btn">🔍 Diagnostic complet</a>
        </div>
    </div>
</body>
</html>

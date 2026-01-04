<?php
declare(strict_types=1);

namespace Mini\Core;

final class Router
{
    /** @var array<int, array{0:string,1:string,2:array{0:class-string,1:string}} > */
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $basePath = dirname($scriptName);

        if ($basePath === '/' || $basePath === '\\' || $basePath === '.') {
            $basePath = '';
        } else {
            $basePath = str_replace('\\', '/', $basePath);
            $basePath = '/' . trim($basePath, '/');
        }

        if ($basePath !== '' && $basePath !== '/' && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
            if ($path === '') {
                $path = '/';
            }
        }

        $path = '/' . trim($path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        foreach ($this->routes as [$routeMethod, $routePath, $handler]) {
            if ($method === $routeMethod && $path === $routePath) {
                [$class, $action] = $handler;
                $controller = new $class();
                $controller->$action();
                return;
            }
        }
        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
        echo '<p><strong>Méthode:</strong> ' . htmlspecialchars($method) . '</p>';
        echo '<p><strong>URI originale:</strong> ' . htmlspecialchars($uri) . '</p>';
        echo '<p><strong>Chemin extrait:</strong> ' . htmlspecialchars($path) . '</p>';
        echo '<p><strong>Chemin de base:</strong> ' . htmlspecialchars($basePath ?: '(aucun)') . '</p>';
        echo '<p><strong>SCRIPT_NAME:</strong> ' . htmlspecialchars($scriptName) . '</p>';
        echo '<h2>Routes disponibles:</h2><ul>';
        foreach ($this->routes as [$routeMethod, $routePath, $handler]) {
            $match = ($method === $routeMethod && $path === $routePath) ? ' ✅' : '';
            echo '<li>[' . htmlspecialchars($routeMethod) . '] ' . htmlspecialchars($routePath) . $match . '</li>';
        }
        echo '</ul>';
    }
}



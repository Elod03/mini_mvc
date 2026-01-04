<?php
declare(strict_types=1);

namespace Mini\Core;

class Controller
{
    /**
     * Constructeur qui démarre la session si elle n'est pas déjà démarrée
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
     */
    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            $redirect = urlencode($_SERVER['REQUEST_URI'] ?? '/');
            header('Location: /auth/login?redirect=' . $redirect);
            exit;
        }
    }

    /**
     * Récupère l'ID de l'utilisateur connecté
     */
    protected function getUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    protected function render(string $view, array $params = []): void
    {
        extract(array: $params);
        $viewFile = dirname(__DIR__) . '/Views/' . $view . '.php';
        $layoutFile = dirname(__DIR__) . '/Views/layout.php';

        ob_start();
        require $viewFile;

        $content = ob_get_clean();

        require $layoutFile;
    }
}



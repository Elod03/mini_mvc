<?php

declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\User;
use Mini\Models\Product;

final class HomeController extends Controller
{
    public function index(): void
    {
        $products = Product::getAll();

        $this->render('home/index', params: [
            'title' => 'Accueil - Mini MVC',
            'products' => $products
        ]);
    }

    public function users(): void
    {
        $users = User::getAll();

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($users, JSON_PRETTY_PRINT);
    }

    public function showCreateUserForm(): void
    {
        $this->render('home/create-user', params: [
            'title' => 'Créer un utilisateur'
        ]);
    }

    public function createUser(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée. Utilisez POST.'], JSON_PRETTY_PRINT);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if ($input === null) {
            $input = $_POST;
        }

        if (empty($input['nom']) || empty($input['email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Les champs "nom" et "email" sont requis.'], JSON_PRETTY_PRINT);
            return;
        }

        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'Format d\'email invalide.'], JSON_PRETTY_PRINT);
            return;
        }

        $user = new User();
        $user->setNom($input['nom']);
        $user->setEmail($input['email']);

        if ($user->save()) {
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Utilisateur créé avec succès.',
                'user' => [
                    'nom' => $user->getnom(),
                    'email' => $user->getEmail()
                ]
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la création de l\'utilisateur.'], JSON_PRETTY_PRINT);
        }
    }
}
<?php

declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\User;

final class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     */
    public function showLogin(): void
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            return;
        }
        
        $this->render('auth/login', params: [
            'title' => 'Connexion'
        ]);
    }

    /**
     * Traite la connexion
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /auth/login');
            return;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->render('auth/login', params: [
                'title' => 'Connexion',
                'error' => 'Veuillez remplir tous les champs.',
                'old_email' => $email
            ]);
            return;
        }

        $userData = User::findByEmail($email);
        
        if (!$userData) {
            $this->render('auth/login', params: [
                'title' => 'Connexion',
                'error' => 'Email ou mot de passe incorrect.',
                'old_email' => $email
            ]);
            return;
        }

        if (empty($userData['password']) || !password_verify($password, $userData['password'])) {
            $this->render('auth/login', params: [
                'title' => 'Connexion',
                'error' => 'Email ou mot de passe incorrect.',
                'old_email' => $email
            ]);
            return;
        }

        session_start();
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user_nom'] = $userData['nom'];
        $_SESSION['user_email'] = $userData['email'];

        $redirect = $_GET['redirect'] ?? '/';
        header('Location: ' . $redirect);
    }

    /**
     * Affiche le formulaire d'inscription
     */
    public function showRegister(): void
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            return;
        }
        
        $this->render('auth/register', params: [
            'title' => 'Inscription'
        ]);
    }

    /**
     * Traite l'inscription
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /auth/register');
            return;
        }

        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        $errors = [];

        if (empty($nom)) {
            $errors[] = 'Le nom est requis.';
        }

        if (empty($email)) {
            $errors[] = 'L\'email est requis.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format d\'email invalide.';
        } elseif (User::findByEmail($email)) {
            $errors[] = 'Cet email est déjà utilisé.';
        }

        if (empty($password)) {
            $errors[] = 'Le mot de passe est requis.';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
        }

        if ($password !== $password_confirm) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }

        if (!empty($errors)) {
            $this->render('auth/register', params: [
                'title' => 'Inscription',
                'errors' => $errors,
                'old_nom' => $nom,
                'old_email' => $email
            ]);
            return;
        }

        $user = new User();
        $user->setNom($nom);
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));

        if ($user->save()) {
            session_start();
            $userData = User::findByEmail($email);
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['user_nom'] = $userData['nom'];
            $_SESSION['user_email'] = $userData['email'];

            header('Location: /?success=registered');
        } else {
            $this->render('auth/register', params: [
                'title' => 'Inscription',
                'errors' => ['Erreur lors de la création du compte. Veuillez réessayer.'],
                'old_nom' => $nom,
                'old_email' => $email
            ]);
        }
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout(): void
    {
        session_start();
        session_destroy();
        header('Location: /?success=logged_out');
    }
}
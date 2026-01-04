<?php

declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\Order;
use Mini\Models\Cart;

final class OrderController extends Controller
{
    /**
     * Affiche toutes les commandes d'un utilisateur
     */
    public function listByUser(): void
    {
        $this->requireAuth();
        $user_id = $this->getUserId();
        
        $orders = Order::getByUserId($user_id);
        
        $this->render('order/list', params: [
            'title' => 'Mes commandes',
            'orders' => $orders,
            'user_id' => $user_id
        ]);
    }

    /**
     * Affiche toutes les commandes validées
     */
    public function listValidated(): void
    {
        $orders = Order::getValidatedOrders();
        
        $this->render('order/validated', params: [
            'title' => 'Commandes validées',
            'orders' => $orders
        ]);
    }

    /**
     * Affiche les détails d'une commande
     */
    public function show(): void
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Le paramètre id est requis.'], JSON_PRETTY_PRINT);
            return;
        }
        
        $order = Order::findByIdWithProducts($id);
        
        $message = null;
        $messageType = null;
        
        if (isset($_GET['success']) && $_GET['success'] === 'created') {
            $message = 'Commande créée avec succès !';
            $messageType = 'success';
        }
        
        if (!$order) {
            $this->render('order/not-found', params: [
                'title' => 'Commande introuvable'
            ]);
            return;
        }
        
        $this->render('order/show', params: [
            'title' => 'Détails de la commande #' . $id,
            'order' => $order,
            'message' => $message,
            'messageType' => $messageType
        ]);
    }

    /**
     * Crée une commande à partir du panier
     */
    public function create(): void
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            return;
        }
        
        $user_id = $this->getUserId();

        $cartItems = Cart::getByUserId($user_id);
        if (empty($cartItems)) {
            header('Location: /cart?error=empty_cart');
            return;
        }

        $orderId = Order::createFromCart($user_id);
        
        if ($orderId) {
            header('Location: /orders/show?id=' . $orderId . '&success=created');
        } else {
            header('Location: /cart?error=create_failed');
        }
    }

    /**
     * Met à jour le statut d'une commande
     */
    public function updateStatus(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée.'], JSON_PRETTY_PRINT);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input === null) {
            $input = $_POST;
        }
        
        if (empty($input['order_id']) || empty($input['statut'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Les champs "order_id" et "statut" sont requis.'], JSON_PRETTY_PRINT);
            return;
        }
        
        $validStatuses = ['en_attente', 'validee', 'annulee'];
        if (!in_array($input['statut'], $validStatuses)) {
            http_response_code(400);
            echo json_encode(['error' => 'Statut invalide. Valeurs acceptées: ' . implode(', ', $validStatuses)], JSON_PRETTY_PRINT);
            return;
        }
        
        $order = new Order();
        $order->setId($input['order_id']);
        $order->setStatut($input['statut']);

        $pdo = \Mini\Core\Database::getPDO();
        $stmt = $pdo->prepare("SELECT total FROM commande WHERE id = ?");
        $stmt->execute([$input['order_id']]);
        $existing = $stmt->fetch(\PDO::FETCH_ASSOC);
        $order->setTotal($existing['total'] ?? 0);
        
        if ($order->update()) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Statut de la commande mis à jour avec succès.'
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la mise à jour.'], JSON_PRETTY_PRINT);
        }
    }
}


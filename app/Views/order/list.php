<!-- Liste des commandes -->
<div class="container fade-in">
    <div class="flex-between mb-3">
        <h2 style="font-size: 2rem; font-weight: 700; color: var(--gray-900);">Mes commandes</h2>
        <a href="/products" class="btn btn-primary">
            ← Retour aux produits
        </a>
    </div>
    
    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📋</div>
            <h3 class="empty-state-title">Aucune commande</h3>
            <p class="empty-state-text">Vous n'avez pas encore passé de commande.</p>
            <a href="/products" class="btn btn-primary">
                Voir les produits
            </a>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="flex-between" style="flex-wrap: wrap; gap: 1.25rem;">
                        <!-- Informations principales -->
                        <div style="flex: 1;">
                            <div class="flex gap-2" style="align-items: center; margin-bottom: 1rem; flex-wrap: wrap;">
                                <h3 style="margin: 0; color: var(--gray-900); font-size: 1.5rem; font-weight: 600;">
                                    Commande #<?= htmlspecialchars($order['id']) ?>
                                </h3>
                                <span class="order-status <?= htmlspecialchars($order['statut']) ?>">
                                    <?php 
                                        if ($order['statut'] === 'validee') {
                                            echo '✅ Validée';
                                        } elseif ($order['statut'] === 'en_attente') {
                                            echo '⏳ En attente';
                                        } elseif ($order['statut'] === 'annulee') {
                                            echo '❌ Annulée';
                                        } else {
                                            echo htmlspecialchars($order['statut']);
                                        }
                                    ?>
                                </span>
                            </div>
                            
                            <div style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 0.75rem;">
                                <strong>Date :</strong> <?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?>
                            </div>
                            
                            <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary-color);">
                                <?= number_format((float)$order['total'], 2, ',', ' ') ?> €
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div>
                            <a href="/orders/show?id=<?= htmlspecialchars($order['id']) ?>" class="btn btn-primary">
                                👁️ Voir les détails
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="text-center mt-4">
        <a href="/" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">← Retour à l'accueil</a>
    </div>
</div>

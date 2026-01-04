<!-- Détails d'une commande -->
<div class="container fade-in">
    <!-- Messages de succès/erreur -->
    <?php if (isset($message) && $message): ?>
        <div class="alert <?= $messageType === 'success' ? 'alert-success' : 'alert-error' ?>">
            <?= $messageType === 'success' ? '✅ ' : '❌ ' ?><?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    
    <?php if (!$order): ?>
        <div class="empty-state">
            <div class="empty-state-icon">❌</div>
            <h3 class="empty-state-title">Commande introuvable</h3>
            <p class="empty-state-text">La commande que vous recherchez n'existe pas ou a été supprimée.</p>
            <a href="/orders" class="btn btn-primary">← Retour à mes commandes</a>
        </div>
    <?php else: ?>
        <div class="flex-between mb-3">
            <h2 style="font-size: 2rem; font-weight: 700; color: var(--gray-900);">Détails de la commande #<?= htmlspecialchars($order['id']) ?></h2>
            <a href="/orders" class="btn btn-secondary">
                ← Retour à mes commandes
            </a>
        </div>
        
        <!-- Informations de la commande -->
        <div style="background-color: var(--white); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 2rem; box-shadow: var(--shadow);">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <div style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 0.5rem;">Statut</div>
                    <div>
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
                </div>
                <div>
                    <div style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 0.5rem;">Date de commande</div>
                    <div style="font-weight: 600; color: var(--gray-900);">
                        <?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?>
                    </div>
                </div>
                <div>
                    <div style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 0.5rem;">Client</div>
                    <div style="font-weight: 600; color: var(--gray-900);">
                        <?= htmlspecialchars($order['user_nom'] ?? 'Utilisateur #' . $order['user_id']) ?>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--gray-500);">
                        <?= htmlspecialchars($order['user_email'] ?? '') ?>
                    </div>
                </div>
                <div>
                    <div style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 0.5rem;">Total</div>
                    <div style="font-size: 2rem; font-weight: 700; color: var(--primary-color);">
                        <?= number_format((float)$order['total'], 2, ',', ' ') ?> €
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Liste des produits -->
        <div style="background-color: var(--white); border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow);">
            <h3 style="margin: 0 0 1.5rem 0; color: var(--gray-900); font-size: 1.5rem; font-weight: 600;">Produits commandés</h3>
            
            <?php if (empty($order['products'])): ?>
                <p style="color: var(--gray-600);">Aucun produit dans cette commande.</p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php foreach ($order['products'] as $product): ?>
                        <div style="border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 1rem; display: flex; gap: 1.25rem; align-items: center;">
                            <!-- Image du produit -->
                            <div style="width: 80px; height: 80px; flex-shrink: 0;">
                                <?php if (!empty($product['image_url'])): ?>
                                    <img 
                                        src="<?= htmlspecialchars($product['image_url']) ?>" 
                                        alt="<?= htmlspecialchars($product['product_nom']) ?>" 
                                        style="width: 100%; height: 100%; object-fit: contain; border-radius: var(--radius); border: 1px solid var(--gray-200);"
                                        onerror="this.style.display='none'"
                                    >
                                <?php else: ?>
                                    <div style="width: 100%; height: 100%; background-color: var(--gray-100); border-radius: var(--radius); display: flex; align-items: center; justify-content: center; border: 1px solid var(--gray-200);">
                                        <span style="color: var(--gray-400); font-size: 10px;">Pas d'image</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Informations du produit -->
                            <div style="flex: 1;">
                                <h4 style="margin: 0 0 0.375rem 0; color: var(--gray-900); font-size: 1rem; font-weight: 600;">
                                    <?= htmlspecialchars($product['product_nom']) ?>
                                </h4>
                                <?php if (!empty($product['categorie_nom'])): ?>
                                    <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 0.375rem;">
                                        📁 <?= htmlspecialchars($product['categorie_nom']) ?>
                                    </div>
                                <?php endif; ?>
                                <div style="font-size: 0.875rem; color: var(--gray-600);">
                                    Quantité : <strong><?= htmlspecialchars($product['quantite']) ?></strong>
                                </div>
                            </div>
                            
                            <!-- Prix -->
                            <div style="text-align: right;">
                                <div style="font-size: 1.125rem; font-weight: 700; color: var(--primary-color);">
                                    <?= number_format((float)$product['prix_unitaire'], 2, ',', ' ') ?> €
                                </div>
                                <div style="font-size: 0.75rem; color: var(--gray-500); margin-top: 0.375rem;">
                                    Sous-total : <?= number_format((float)$product['prix_unitaire'] * (int)$product['quantite'], 2, ',', ' ') ?> €
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

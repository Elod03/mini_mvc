<!-- Vue du panier -->
<div class="container fade-in">
    <div class="flex-between mb-3">
        <h2 style="font-size: 2rem; font-weight: 700; color: var(--gray-900);">Mon panier</h2>
        <a href="/products" class="btn btn-primary">
            ← Continuer les achats
        </a>
    </div>
    
    <!-- Messages de succès/erreur -->
    <?php if (isset($message) && $message): ?>
        <div class="alert <?= $messageType === 'success' ? 'alert-success' : 'alert-error' ?>">
            <?= $messageType === 'success' ? '✅ ' : '❌ ' ?><?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($cartItems)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">🛒</div>
            <h3 class="empty-state-title">Votre panier est vide</h3>
            <p class="empty-state-text">Ajoutez des produits à votre panier pour commencer vos achats.</p>
            <a href="/products" class="btn btn-primary">
                Voir les produits
            </a>
        </div>
    <?php else: ?>
        <div class="cart-container">
            <!-- Liste des articles -->
            <div>
                <h3 style="margin-bottom: 1.5rem; color: var(--gray-900); font-size: 1.5rem; font-weight: 600;">Articles dans votre panier</h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item">
                            <!-- Image du produit -->
                            <div class="cart-item-image">
                                <?php if (!empty($item['image_url'])): ?>
                                    <img 
                                        src="<?= htmlspecialchars($item['image_url']) ?>" 
                                        alt="<?= htmlspecialchars($item['nom']) ?>"
                                        onerror="this.style.display='none'"
                                    >
                                <?php else: ?>
                                    <div style="width: 100%; height: 100%; background-color: var(--gray-100); display: flex; align-items: center; justify-content: center;">
                                        <span style="color: var(--gray-400); font-size: 12px;">Pas d'image</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Informations du produit -->
                            <div class="cart-item-info">
                                <h4 class="cart-item-title">
                                    <a href="/products/show?id=<?= htmlspecialchars($item['id']) ?>">
                                        <?= htmlspecialchars($item['nom']) ?>
                                    </a>
                                </h4>
                                
                                <?php if (!empty($item['categorie_nom'])): ?>
                                    <div style="margin-bottom: 0.5rem;">
                                        <span style="font-size: 0.75rem; color: var(--gray-500);">📁 <?= htmlspecialchars($item['categorie_nom']) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div style="font-size: 1.25rem; font-weight: 700; color: var(--primary-color); margin-bottom: 1rem;">
                                    <?= number_format((float)$item['prix'], 2, ',', ' ') ?> €
                                </div>
                                
                                <!-- Gestion de la quantité -->
                                <div class="flex gap-2" style="align-items: center; flex-wrap: wrap;">
                                    <form method="POST" action="/cart/update" class="flex gap-2" style="align-items: center;">
                                        <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['panier_id']) ?>">
                                        <label for="quantite_<?= htmlspecialchars($item['panier_id']) ?>" style="font-size: 0.875rem; color: var(--gray-600);">Quantité :</label>
                                        <input 
                                            type="number" 
                                            id="quantite_<?= htmlspecialchars($item['panier_id']) ?>" 
                                            name="quantite" 
                                            value="<?= htmlspecialchars($item['quantite']) ?>" 
                                            min="1" 
                                            max="<?= htmlspecialchars($item['stock']) ?>"
                                            class="form-input"
                                            style="width: 80px;"
                                            required
                                        >
                                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                            Mettre à jour
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="/cart/remove" style="margin: 0;">
                                        <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['panier_id']) ?>">
                                        <button 
                                            type="submit" 
                                            class="btn btn-danger"
                                            style="padding: 0.5rem 1rem; font-size: 0.875rem;"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')"
                                        >
                                            🗑️ Supprimer
                                        </button>
                                    </form>
                                </div>
                                
                                <div style="margin-top: 0.75rem; font-size: 0.875rem; color: var(--gray-600);">
                                    Sous-total : <strong style="color: var(--gray-900);"><?= number_format((float)$item['prix'] * (int)$item['quantite'], 2, ',', ' ') ?> €</strong>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Bouton vider le panier -->
                <div class="mt-3">
                    <form method="POST" action="/cart/clear">
                        <button 
                            type="submit" 
                            class="btn btn-danger"
                            onclick="return confirm('Êtes-vous sûr de vouloir vider tout votre panier ?')"
                        >
                            🗑️ Vider le panier
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Résumé de la commande -->
            <div>
                <div class="cart-summary">
                    <h3 class="cart-summary-title">Résumé de la commande</h3>
                    
                    <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--gray-200);">
                        <div class="cart-summary-row">
                            <span style="color: var(--gray-600);">Sous-total :</span>
                            <span style="font-weight: 600;"><?= number_format((float)$total, 2, ',', ' ') ?> €</span>
                        </div>
                        <div class="cart-summary-row">
                            <span style="color: var(--gray-600);">Frais de livraison :</span>
                            <span style="font-weight: 600; color: var(--success-color);">Gratuit</span>
                        </div>
                    </div>
                    
                    <div class="cart-summary-total">
                        <span>Total :</span>
                        <span style="color: var(--primary-color);"><?= number_format((float)$total, 2, ',', ' ') ?> €</span>
                    </div>
                    
                    <form method="POST" action="/orders/create" class="mt-3">
                        <button type="submit" class="btn btn-success" style="width: 100%; padding: 1rem; font-size: 1.125rem;">
                            ✅ Valider la commande
                        </button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="/products" style="color: var(--primary-color); text-decoration: none; font-size: 0.875rem;">
                            ← Continuer les achats
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

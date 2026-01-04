<!-- Détails du produit -->
<div class="container fade-in">
    <?php if (!$product): ?>
        <div class="empty-state">
            <div class="empty-state-icon">❌</div>
            <h3 class="empty-state-title">Produit introuvable</h3>
            <p class="empty-state-text">Le produit que vous recherchez n'existe pas ou a été supprimé.</p>
            <a href="/products" class="btn btn-primary">← Retour à la liste des produits</a>
        </div>
    <?php else: ?>
        <div class="product-detail">
            <!-- Image du produit -->
            <div class="product-detail-image">
                <?php if (!empty($product['image_url'])): ?>
                    <img 
                        src="<?= htmlspecialchars($product['image_url']) ?>" 
                        alt="<?= htmlspecialchars($product['nom']) ?>"
                        onerror="this.style.display='none'"
                    >
                <?php else: ?>
                    <div style="width: 100%; height: 400px; background-color: var(--gray-100); border-radius: var(--radius); display: flex; align-items: center; justify-content: center;">
                        <span style="color: var(--gray-400); font-size: 18px;">Aucune image disponible</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Informations du produit -->
            <div class="product-detail-info">
                <h1 class="product-detail-title">
                    <?= htmlspecialchars($product['nom']) ?>
                </h1>
                
                <?php if (!empty($product['categorie_nom'])): ?>
                    <div class="mb-2">
                        <span style="padding: 0.375rem 0.875rem; background-color: #dbeafe; color: var(--primary-color); border-radius: 9999px; font-size: 0.875rem; font-weight: 500;">
                            📁 <?= htmlspecialchars($product['categorie_nom']) ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <div class="mb-3">
                    <div class="product-detail-price">
                        <?= number_format((float)$product['prix'], 2, ',', ' ') ?> €
                    </div>
                    <div style="font-size: 1rem; color: <?= $product['stock'] > 0 ? 'var(--success-color)' : 'var(--danger-color)' ?>; font-weight: 600;">
                        <?php if ($product['stock'] > 0): ?>
                            ✅ En stock (<?= htmlspecialchars($product['stock']) ?> disponible<?= $product['stock'] > 1 ? 's' : '' ?>)
                        <?php else: ?>
                            ❌ Stock épuisé
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!empty($product['description'])): ?>
                    <div class="product-detail-description">
                        <h3 style="margin: 0 0 1rem 0; color: var(--gray-900); font-size: 1.25rem; font-weight: 600;">Description</h3>
                        <p style="margin: 0; color: var(--gray-700); line-height: 1.8; white-space: pre-wrap;">
                            <?= htmlspecialchars($product['description']) ?>
                        </p>
                    </div>
                <?php endif; ?>
                
                <!-- Formulaire d'ajout au panier -->
                <?php if ($product['stock'] > 0): ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form method="POST" action="/cart/add-from-form" class="mt-3">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                            <div class="flex gap-2" style="align-items: center;">
                                <label for="quantite" style="font-weight: 600; color: var(--gray-700);">Quantité :</label>
                                <input 
                                    type="number" 
                                    id="quantite" 
                                    name="quantite" 
                                    value="1" 
                                    min="1" 
                                    max="<?= htmlspecialchars($product['stock']) ?>"
                                    class="form-input"
                                    style="width: 100px;"
                                    required
                                >
                                <button type="submit" class="btn btn-success" style="flex: 1;">
                                    🛒 Ajouter au panier
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning mt-3">
                            <p style="margin: 0 0 0.75rem 0;">⚠️ Vous devez être connecté pour ajouter des produits au panier.</p>
                            <a href="/auth/login" class="btn btn-primary">
                                🔐 Se connecter
                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning mt-3">
                        ⚠️ Ce produit n'est actuellement pas disponible en stock.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="flex-between mt-4" style="padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
            <a href="/products" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">← Retour à la liste des produits</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/cart" class="btn btn-warning">
                    🛒 Voir mon panier
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

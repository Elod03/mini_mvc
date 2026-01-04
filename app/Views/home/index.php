<!-- Page d'accueil avec liste des produits -->
<div class="container fade-in">
    <div class="hero">
        <h1>Découvrez l'excellence à chaque clic</h1>
        <p>Une sélection soignée de produits de qualité, livrés avec passion</p>
    </div>
    
    <?php if (empty($products)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📦</div>
            <h3 class="empty-state-title">Aucun produit disponible</h3>
            <p class="empty-state-text">Les produits seront bientôt disponibles.</p>
        </div>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <!-- Image du produit -->
                    <div class="product-card-image">
                        <?php if (!empty($product['image_url'])): ?>
                            <img 
                                src="<?= htmlspecialchars($product['image_url']) ?>" 
                                alt="<?= htmlspecialchars($product['nom']) ?>"
                                onerror="this.style.display='none'"
                            >
                        <?php else: ?>
                            <span style="color: #9ca3af; font-size: 14px;">Aucune image</span>
                        <?php endif; ?>
                        <?php if ($product['stock'] > 10): ?>
                            <span class="product-badge new">✨ Nouveau</span>
                        <?php elseif ($product['stock'] > 0 && $product['stock'] <= 10): ?>
                            <span class="product-badge stock-low">⚡ Stock limité</span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Informations du produit -->
                    <div class="product-card-body">
                        <h3 class="product-card-title">
                            <?= htmlspecialchars($product['nom']) ?>
                        </h3>
                        
                        <?php if (!empty($product['description'])): ?>
                            <p class="product-card-description">
                                <?= htmlspecialchars($product['description']) ?>
                            </p>
                        <?php endif; ?>
                        
                        <div class="product-card-footer">
                            <div>
                                <div class="product-price">
                                    <?= number_format((float)$product['prix'], 2, ',', ' ') ?> €
                                </div>
                                <div class="product-stock <?= $product['stock'] > 10 ? 'in-stock' : ($product['stock'] > 0 ? 'low-stock' : 'out-of-stock') ?>">
                                    <?php if ($product['stock'] > 10): ?>
                                        ✓ En stock (<?= htmlspecialchars($product['stock']) ?>)
                                    <?php elseif ($product['stock'] > 0): ?>
                                        ⚠ Stock limité (<?= htmlspecialchars($product['stock']) ?>)
                                    <?php else: ?>
                                        ✗ Épuisé
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($product['categorie_nom'])): ?>
                                    <div class="product-category">
                                        📁 <?= htmlspecialchars($product['categorie_nom']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="product-card-actions">
                            <a href="/products/show?id=<?= htmlspecialchars($product['id']) ?>" class="btn btn-secondary">
                                👁️ Détails
                            </a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <form method="POST" action="/cart/add-from-form" style="flex: 1; margin: 0;">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                                    <input type="hidden" name="quantite" value="1">
                                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
                                    <button type="submit" class="btn btn-success" <?= $product['stock'] <= 0 ? 'disabled title="Stock épuisé"' : '' ?>>
                                        🛒 Panier
                                    </button>
                                </form>
                            <?php else: ?>
                                <a href="/auth/login" class="btn btn-warning">
                                    🔐 Connexion
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="text-center mt-4">
        <a href="/products" class="btn btn-primary" style="font-size: 1.125rem; padding: 1rem 2.5rem;">
            ✨ Explorer tous nos produits →
        </a>
    </div>
</div>

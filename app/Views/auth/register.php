<!-- Formulaire d'inscription -->
<div class="container fade-in">
    <div style="max-width: 500px; margin: 3rem auto; padding: 2.5rem; background-color: var(--white); border-radius: var(--radius-lg); box-shadow: var(--shadow-xl);">
        <h2 style="text-align: center; color: var(--gray-900); margin-bottom: 2rem; font-size: 2rem; font-weight: 700;">Inscription</h2>
        
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Erreurs :</strong>
                <ul style="margin: 0.75rem 0 0 1.5rem; padding: 0;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/auth/register">
            <div class="form-group">
                <label for="nom" class="form-label">Nom :</label>
                <input 
                    type="text" 
                    id="nom" 
                    name="nom" 
                    value="<?= htmlspecialchars($old_nom ?? '') ?>" 
                    required
                    class="form-input"
                    placeholder="Votre nom"
                >
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email :</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?= htmlspecialchars($old_email ?? '') ?>" 
                    required
                    class="form-input"
                    placeholder="votre@email.com"
                >
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Mot de passe :</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    minlength="6"
                    class="form-input"
                    placeholder="Minimum 6 caractères"
                >
                <small class="form-small">Le mot de passe doit contenir au moins 6 caractères.</small>
            </div>
            
            <div class="form-group">
                <label for="password_confirm" class="form-label">Confirmer le mot de passe :</label>
                <input 
                    type="password" 
                    id="password_confirm" 
                    name="password_confirm" 
                    required
                    minlength="6"
                    class="form-input"
                    placeholder="Répétez le mot de passe"
                >
            </div>
            
            <button type="submit" class="btn btn-success" style="width: 100%; padding: 0.875rem; font-size: 1rem;">
                Créer mon compte
            </button>
        </form>
        
        <div style="margin-top: 1.5rem; text-align: center; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
            <p style="color: var(--gray-600); margin-bottom: 0.75rem;">Vous avez déjà un compte ?</p>
            <a href="/auth/login" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">
                Se connecter
            </a>
        </div>
        
        <div style="margin-top: 1.5rem; text-align: center;">
            <a href="/" style="color: var(--gray-500); text-decoration: none;">← Retour à l'accueil</a>
        </div>
    </div>
</div>

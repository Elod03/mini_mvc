<!-- Formulaire de connexion -->
<div class="container fade-in">
    <div style="max-width: 500px; margin: 3rem auto; padding: 2.5rem; background-color: var(--white); border-radius: var(--radius-lg); box-shadow: var(--shadow-xl);">
        <h2 style="text-align: center; color: var(--gray-900); margin-bottom: 2rem; font-size: 2rem; font-weight: 700;">Connexion</h2>
        
        <?php if (isset($error) && $error): ?>
            <div class="alert alert-error">
                ❌ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/auth/login">
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
                    class="form-input"
                    placeholder="••••••••"
                >
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem; font-size: 1rem;">
                Se connecter
            </button>
        </form>
        
        <div style="margin-top: 1.5rem; text-align: center; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
            <p style="color: var(--gray-600); margin-bottom: 0.75rem;">Vous n'avez pas de compte ?</p>
            <a href="/auth/register" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">
                Créer un compte
            </a>
        </div>
        
        <div style="margin-top: 1.5rem; text-align: center;">
            <a href="/" style="color: var(--gray-500); text-decoration: none;">← Retour à l'accueil</a>
        </div>
    </div>
</div>

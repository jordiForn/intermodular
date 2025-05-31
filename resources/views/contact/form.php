<?php use App\Core\Auth; ?>
<?php use App\Http\Middlewares\Security\CsrfMiddleware; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Contacta amb nosaltres</h4>
                </div>
                <div class="card-body">
                    <?php include __DIR__ . '/../partials/message.php'; ?>
                    <?php include __DIR__ . '/../partials/errors.php'; ?>
                    
                    <p class="mb-4">Completa el formulari següent per enviar-nos la teva consulta. Ens posarem en contacte amb tu el més aviat possible.</p>
                    
                    <form action="<?= BASE_URL ?>/contact/store.php" method="POST">
                        <?= CsrfMiddleware::tokenField(); ?>
                        
                        <div class="mb-3 auth-field" <?= Auth::check() ? 'style="display:none;"' : '' ?>>
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars(session()->getFlash('old')['name'] ?? '') ?>" <?= Auth::check() ? '' : 'required' ?>>
                        </div>
                        
                        <div class="mb-3 auth-field" <?= Auth::check() ? 'style="display:none;"' : '' ?>>
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars(session()->getFlash('old')['email'] ?? '') ?>" <?= Auth::check() ? '' : 'required' ?>>
                        </div>
                        
                        <div class="mb-3 auth-field" <?= Auth::check() ? 'style="display:none;"' : '' ?>>
                            <label for="phone" class="form-label">Telèfon (opcional)</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars(session()->getFlash('old')['phone'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-3">
    <label for="consulta" class="form-label">La teva consulta</label>
    <textarea class="form-control <?= isset($errors['consulta']) ? 'is-invalid' : '' ?>" id="consulta" name="consulta" rows="5" required><?= htmlspecialchars(session()->getFlash('old')['consulta'] ?? '') ?></textarea>
    <?php if (isset($errors['consulta'])): ?>
        <div class="invalid-feedback"><?= $errors['consulta'] ?></div>
    <?php endif; ?>
</div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Enviar consulta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // If user is authenticated, we'll use their session data
    const isAuthenticated = <?= json_encode(Auth::check()) ?>;
    
    if (isAuthenticated) {
        // Hide fields that are already provided by authentication
        document.querySelectorAll('.auth-field').forEach(field => {
            field.style.display = 'none';
        });
    }
});
</script>

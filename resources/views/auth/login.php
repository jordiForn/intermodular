<?php use App\Http\Middlewares\Security\CsrfMiddleware; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Iniciar sessió</h4>
                </div>
                <div class="card-body">
                    <?php include __DIR__ . '/../partials/message.php'; ?>
                    <?php include __DIR__ . '/../partials/errors.php'; ?>
                    
                    <form action="<?= BASE_URL ?>/auth/login.php" method="POST">
                        <?php 
                        // Add CSRF token field
                        echo CsrfMiddleware::tokenField();
                        ?>
                        
                        <div class="mb-3">
                            <label for="nom_login" class="form-label">Nom d'usuari</label>
                            <input type="text" class="form-control <?= isset($errors['nom_login']) ? 'is-invalid' : '' ?>" id="nom_login" name="nom_login" value="<?= htmlspecialchars(session()->getFlash('old')['nom_login'] ?? '') ?>">
                            <?php if (isset($errors['nom_login'])): ?>
                                <div class="invalid-feedback"><?= $errors['nom_login'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contrasenya</label>
                            <input type="password" class="form-control <?= isset($errors['contrasena']) ? 'is-invalid' : '' ?>" id="contrasena" name="contrasena">
                            <?php if (isset($errors['contrasena'])): ?>
                                <div class="invalid-feedback"><?= $errors['contrasena'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Iniciar sessió</button>
                        </div>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <p>No tens compte? <a href="<?= BASE_URL ?>/auth/show-register.php">Registra't</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

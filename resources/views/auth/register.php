<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Registre d'usuari</h4>
                </div>
                <div class="card-body">
                    <?php include __DIR__ . '/../partials/message.php'; ?>
                    <?php include __DIR__ . '/../partials/errors.php'; ?>
                    
                    <form action="<?= BASE_URL ?>/auth/register.php" method="POST">
                        <?php 
                        // Add CSRF token field
                        use App\Http\Middlewares\Security\CsrfMiddleware;
                        echo CsrfMiddleware::tokenField();
                        ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom</label>
                                    <input type="text" class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>" id="nom" name="nom" value="<?= htmlspecialchars(session()->getFlash('old')['nom'] ?? '') ?>">
                                    <?php if (isset($errors['nom'])): ?>
                                        <div class="invalid-feedback"><?= $errors['nom'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cognom" class="form-label">Cognom</label>
                                    <input type="text" class="form-control <?= isset($errors['cognom']) ? 'is-invalid' : '' ?>" id="cognom" name="cognom" value="<?= htmlspecialchars(session()->getFlash('old')['cognom'] ?? '') ?>">
                                    <?php if (isset($errors['cognom'])): ?>
                                        <div class="invalid-feedback"><?= $errors['cognom'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= htmlspecialchars(session()->getFlash('old')['email'] ?? '') ?>">
                                    <?php if (isset($errors['email'])): ?>
                                        <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tlf" class="form-label">Telèfon</label>
                                    <input type="text" class="form-control <?= isset($errors['tlf']) ? 'is-invalid' : '' ?>" id="tlf" name="tlf" value="<?= htmlspecialchars(session()->getFlash('old')['tlf'] ?? '') ?>">
                                    <?php if (isset($errors['tlf'])): ?>
                                        <div class="invalid-feedback"><?= $errors['tlf'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nom_login" class="form-label">Nom d'usuari</label>
                            <input type="text" class="form-control <?= isset($errors['nom_login']) ? 'is-invalid' : '' ?>" id="nom_login" name="nom_login" value="<?= htmlspecialchars(session()->getFlash('old')['nom_login'] ?? '') ?>">
                            <?php if (isset($errors['nom_login'])): ?>
                                <div class="invalid-feedback"><?= $errors['nom_login'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contrasena" class="form-label">Contrasenya</label>
                                    <input type="password" class="form-control <?= isset($errors['contrasena']) ? 'is-invalid' : '' ?>" id="contrasena" name="contrasena">
                                    <?php if (isset($errors['contrasena'])): ?>
                                        <div class="invalid-feedback"><?= $errors['contrasena'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contrasena_confirm" class="form-label">Confirmar contrasenya</label>
                                    <input type="password" class="form-control <?= isset($errors['contrasena_confirm']) ? 'is-invalid' : '' ?>" id="contrasena_confirm" name="contrasena_confirm">
                                    <?php if (isset($errors['contrasena_confirm'])): ?>
                                        <div class="invalid-feedback"><?= $errors['contrasena_confirm'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input <?= isset($errors['terms']) ? 'is-invalid' : '' ?>" id="terms" name="terms" <?= session()->getFlash('old')['terms'] ?? false ? 'checked' : '' ?>>
                            <label class="form-check-label" for="terms">Accepto els <a href="#" target="_blank">termes i condicions</a></label>
                            <?php if (isset($errors['terms'])): ?>
                                <div class="invalid-feedback"><?= $errors['terms'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Registrar-se</button>
                        </div>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <p>Ja tens compte? <a href="<?= BASE_URL ?>/auth/show-login.php">Inicia sessió</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

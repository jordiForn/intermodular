<?php use App\Http\Middlewares\Security\CsrfMiddleware; ?>

<div class="container d-flex justify-content-center mt-5">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card  text-white border-0" style="min-height: 350px;">
                <div class="card-header bg-primary  border-0">
                    <h4 class="mb-0">Iniciar sessió</h4>
                </div>
                <div class="card-body d-flex flex-column justify-content-center bg-color-bg ">
                    <div>
                        <?php
                        // Inicializar $errors para que esté disponible en todo el archivo
                        $errors = session()->getFlash('errors', []);
                        $hasMessages = false;
                        ob_start();
                        include __DIR__ . '/../partials/message.php';
                        include __DIR__ . '/../partials/errors.php';
                        $messagesHtml = ob_get_clean();
                        if (trim($messagesHtml) !== '') {
                            $hasMessages = true;
                        }
                        if ($hasMessages) {
                            echo $messagesHtml;
                        }
                        ?>
                    </div>
                    <form action="<?= BASE_URL ?>/auth/login.php" method="POST">
                        <?php 
                        // Add CSRF token field
                        echo CsrfMiddleware::tokenField();
                        ?>
                        <div class="mb-3">
                            <label for="nom_login" class="form-label text-dark">Nom d'usuari</label>
                            <input type="text" class="form-control bg-light text-dark <?= isset($errors['nom_login']) ? 'is-invalid' : '' ?>" id="nom_login" name="nom_login" value="<?= htmlspecialchars(session()->getFlash('old')['nom_login'] ?? '') ?>">
                            <?php if (isset($errors['nom_login'])): ?>
                                <div class="invalid-feedback bg-light text-danger"><?= $errors['nom_login'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="contrasena" class="form-label text-dark">Contrasenya</label>
                            <input type="password" class="form-control bg-light text-dark <?= isset($errors['contrasena']) ? 'is-invalid' : '' ?>" id="contrasena" name="contrasena">
                            <?php if (isset($errors['contrasena'])): ?>
                                <div class="invalid-feedback bg-light text-danger"><?= $errors['contrasena'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Iniciar sessió</button>
                        </div>
                    </form>
                    <div class="mt-3 text-center">
                        <p class="text-dark">No tens compte? <a href="<?= BASE_URL ?>/auth/show-register.php" class="text-accent fw-bold">Registra't</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

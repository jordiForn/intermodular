<?php
$request = request();
$errors = $request->session()->getFlash('errors', []);
$oldInput = $request->session()->getFlash('old', []);
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-highlight">Crear Nou Usuari</h1>
                <a href="<?= BASE_URL ?>/admin/users/index.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Tornar
                </a>
            </div>

            <!-- Display errors if any -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- User creation form -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-highlight">Informació de l'Usuari</h5>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/admin/users/store.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'usuari <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" 
                                   id="username" name="username" value="<?= $oldInput['username'] ?? '' ?>" required>
                            <?php if (isset($errors['username'])): ?>
                                <div class="invalid-feedback"><?= $errors['username'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                   id="email" name="email" value="<?= $oldInput['email'] ?? '' ?>" required>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contrasenya <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                       id="password" name="password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['password'] ?></div>
                            <?php endif; ?>
                            <div class="form-text">La contrasenya ha de tenir almenys 6 caràcters.</div>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Rol <span class="text-danger">*</span></label>
                            <select class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>" 
                                    id="role" name="role" required>
                                <option value="">Selecciona un rol</option>
                                <option value="user" <?= ($oldInput['role'] ?? '') === 'user' ? 'selected' : '' ?>>Usuari</option>
                                <option value="admin" <?= ($oldInput['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
                            </select>
                            <?php if (isset($errors['role'])): ?>
                                <div class="invalid-feedback"><?= $errors['role'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-outline-secondary me-md-2">Netejar</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Crear Usuari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.querySelector('.toggle-password');
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }
});
</script>

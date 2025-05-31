<?php
$request = request();
$errors = $request->session()->getFlash('errors', []);
$oldInput = $request->session()->getFlash('old', []);

// Use old input if available, otherwise use user data
$username = $oldInput['username'] ?? $user->username;
$email = $oldInput['email'] ?? $user->email;
$role = $oldInput['role'] ?? $user->role;
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-highlight">Editar Usuari</h1>
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

            <!-- User edit form -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-highlight">Informació de l'Usuari</h5>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/admin/users/update.php" method="post">
                        <input type="hidden" name="id" value="<?= $user->id ?>">
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'usuari <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" 
                                   id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
                            <?php if (isset($errors['username'])): ?>
                                <div class="invalid-feedback"><?= $errors['username'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                   id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contrasenya</label>
                            <div class="input-group">
                                <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                       id="password" name="password">
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['password'] ?></div>
                            <?php endif; ?>
                            <div class="form-text">Deixa-ho en blanc per mantenir la contrasenya actual. La nova contrasenya ha de tenir almenys 6 caràcters.</div>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Rol <span class="text-danger">*</span></label>
                            <select class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>" 
                                    id="role" name="role" required>
                                <option value="">Selecciona un rol</option>
                                <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>Usuari</option>
                                <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Administrador</option>
                            </select>
                            <?php if (isset($errors['role'])): ?>
                                <div class="invalid-feedback"><?= $errors['role'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Informació Addicional</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="text" class="form-control" value="<?= date('d/m/Y H:i', strtotime($user->created_at)) ?>" readonly>
                                        <span class="input-group-text">Creat</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                        <input type="text" class="form-control" value="<?= date('d/m/Y H:i', strtotime($user->updated_at)) ?>" readonly>
                                        <span class="input-group-text">Actualitzat</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-outline-secondary me-md-2">Restaurar</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualitzar Usuari
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

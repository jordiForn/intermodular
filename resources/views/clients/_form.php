<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-user-cog"></i> Dades d'usuari</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="nom_login" class="form-label">
                        Nom d'usuari <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control <?= isset($errors['nom_login']) ? 'is-invalid' : '' ?>" 
                           id="nom_login" 
                           name="nom_login" 
                           value="<?= htmlspecialchars($values['nom_login'] ?? '') ?>"
                           required>
                    <?php if (isset($errors['nom_login'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['nom_login']) ?></div>
                    <?php endif; ?>
                    <div class="form-text">Nom únic per iniciar sessió</div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">
                        Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" 
                           class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                           id="email" 
                           name="email" 
                           value="<?= htmlspecialchars($values['email'] ?? '') ?>"
                           required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="contrasena" class="form-label">
                        Contrasenya <?= isset($client) ? '' : '<span class="text-danger">*</span>' ?>
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               class="form-control <?= isset($errors['contrasena']) ? 'is-invalid' : '' ?>" 
                               id="contrasena" 
                               name="contrasena"
                               <?= isset($client) ? '' : 'required' ?>>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <?php if (isset($errors['contrasena'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['contrasena']) ?></div>
                    <?php endif; ?>
                    <?php if (isset($client)): ?>
                        <div class="form-text">Deixa-ho en blanc per mantenir la contrasenya actual</div>
                    <?php endif; ?>
                </div>

                <?php if (isset($client)): ?>
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <select class="form-select" id="rol" name="rol">
                            <option value="0" <?= ($client->rol ?? 0) == 0 ? 'selected' : '' ?>>Client</option>
                            <option value="1" <?= ($client->rol ?? 0) == 1 ? 'selected' : '' ?>>Administrador</option>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-user"></i> Dades personals</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="nom" class="form-label">
                        Nom <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>" 
                           id="nom" 
                           name="nom" 
                           value="<?= htmlspecialchars($values['nom'] ?? '') ?>"
                           required>
                    <?php if (isset($errors['nom'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['nom']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="cognom" class="form-label">Cognom</label>
                    <input type="text" 
                           class="form-control <?= isset($errors['cognom']) ? 'is-invalid' : '' ?>" 
                           id="cognom" 
                           name="cognom" 
                           value="<?= htmlspecialchars($values['cognom'] ?? '') ?>">
                    <?php if (isset($errors['cognom'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['cognom']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="tlf" class="form-label">
                        Telèfon <span class="text-danger">*</span>
                    </label>
                    <input type="tel" 
                           class="form-control <?= isset($errors['tlf']) ? 'is-invalid' : '' ?>" 
                           id="tlf" 
                           name="tlf" 
                           value="<?= htmlspecialchars($values['tlf'] ?? '') ?>"
                           required>
                    <?php if (isset($errors['tlf'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['tlf']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="fas fa-comment"></i> Informació adicional</h6>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label for="consulta" class="form-label">Tipus de consulta</label>
            <select class="form-select <?= isset($errors['consulta']) ? 'is-invalid' : '' ?>" 
                    id="consulta" 
                    name="consulta">
                <option value="">Selecciona una opció</option>
                <option value="jardineria" <?= ($values['consulta'] ?? '') === 'jardineria' ? 'selected' : '' ?>>Jardineria</option>
                <option value="piscines" <?= ($values['consulta'] ?? '') === 'piscines' ? 'selected' : '' ?>>Piscines</option>
                <option value="manteniment" <?= ($values['consulta'] ?? '') === 'manteniment' ? 'selected' : '' ?>>Manteniment</option>
                <option value="altres" <?= ($values['consulta'] ?? '') === 'altres' ? 'selected' : '' ?>>Altres</option>
            </select>
            <?php if (isset($errors['consulta'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['consulta']) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="missatge" class="form-label">Missatge o comentaris</label>
            <textarea class="form-control <?= isset($errors['missatge']) ? 'is-invalid' : '' ?>" 
                      id="missatge" 
                      name="missatge" 
                      rows="4" 
                      placeholder="Escriu aquí qualsevol comentari o informació adicional..."><?= htmlspecialchars($values['missatge'] ?? '') ?></textarea>
            <?php if (isset($errors['missatge'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['missatge']) ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
// Add CSRF token field
use App\Http\Middlewares\Security\CsrfMiddleware;
echo CsrfMiddleware::tokenField();
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('contrasena');
    
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }
    
    // Form validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Si us plau, omple tots els camps obligatoris.');
            }
        });
    }
});
</script>

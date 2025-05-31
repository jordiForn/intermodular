<div class="row">
    <div class="col-md-6">
        <h4>Dades d'usuari</h4>
        
        <div class="mb-3">
            <label for="nom_login" class="form-label">Nom d'usuari</label>
            <input type="text" class="form-control <?= isset($errors['nom_login']) ? 'is-invalid' : '' ?>" id="nom_login" name="nom_login" value="<?= $values['nom_login'] ?? ($user->username ?? '') ?>">
            <?php if (isset($errors['nom_login'])): ?>
                <div class="invalid-feedback"><?= $errors['nom_login'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= $values['email'] ?? ($user->email ?? '') ?>">
            <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback"><?= $errors['email'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="contrasena" class="form-label">Contrasenya</label>
            <input type="password" class="form-control <?= isset($errors['contrasena']) ? 'is-invalid' : '' ?>" id="contrasena" name="contrasena">
            <?php if (isset($errors['contrasena'])): ?>
                <div class="invalid-feedback"><?= $errors['contrasena'] ?></div>
            <?php endif; ?>
            <?php if (isset($client)): ?>
                <small class="form-text text-muted">Deixa-ho en blanc per mantenir la contrasenya actual.</small>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="col-md-6">
        <h4>Dades personals</h4>
        
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>" id="nom" name="nom" value="<?= $values['nom'] ?? '' ?>">
            <?php if (isset($errors['nom'])): ?>
                <div class="invalid-feedback"><?= $errors['nom'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="cognom" class="form-label">Cognom</label>
            <input type="text" class="form-control <?= isset($errors['cognom']) ? 'is-invalid' : '' ?>" id="cognom" name="cognom" value="<?= $values['cognom'] ?? '' ?>">
            <?php if (isset($errors['cognom'])): ?>
                <div class="invalid-feedback"><?= $errors['cognom'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="tlf" class="form-label">Telèfon</label>
            <input type="text" class="form-control <?= isset($errors['tlf']) ? 'is-invalid' : '' ?>" id="tlf" name="tlf" value="<?= $values['tlf'] ?? '' ?>">
            <?php if (isset($errors['tlf'])): ?>
                <div class="invalid-feedback"><?= $errors['tlf'] ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="consulta" class="form-label">Consulta</label>
    <textarea class="form-control <?= isset($errors['consulta']) ? 'is-invalid' : '' ?>" id="consulta" name="consulta" rows="3"><?= $values['consulta'] ?? '' ?></textarea>
    <?php if (isset($errors['consulta'])): ?>
        <div class="invalid-feedback"><?= $errors['consulta'] ?></div>
    <?php endif; ?>
</div>

<div class="mb-3">
    <label for="missatge" class="form-label">Missatge</label>
    <textarea class="form-control <?= isset($errors['missatge']) ? 'is-invalid' : '' ?>" id="missatge" name="missatge" rows="3"><?= $values['missatge'] ?? '' ?></textarea>
    <?php if (isset($errors['missatge'])): ?>
        <div class="invalid-feedback"><?= $errors['missatge'] ?></div>
    <?php endif; ?>
</div>

<?php 
// Add CSRF token field
use App\Http\Middlewares\Security\CsrfMiddleware;
echo CsrfMiddleware::tokenField();
?>

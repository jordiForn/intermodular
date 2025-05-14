<?php if(request()->routeIs('/auth/show-register.php')): ?>
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>"
            id="nombre" name="nombre" value="<?= $values['nombre'] ?>">
        <?php if (isset($errors['nombre'])): ?>
            <div class="invalid-feedback"><?= $errors['nombre'] ?></div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="mb-3">
    <label for="email" class="form-label">Correo Electrónico</label>
    <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
        id="email" name="email" value="<?= $values['email'] ?>">
    <?php if (isset($errors['email'])): ?>
        <div class="invalid-feedback"><?= $errors['email'] ?></div>
    <?php endif; ?>
</div>

<div class="mb-3">
    <label for="password" class="form-label">Contraseña</label>
    <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
        id="password" name="password">
    <?php if (isset($errors['password'])): ?>
        <div class="invalid-feedback"><?= $errors['password'] ?></div>
    <?php endif; ?>
</div>

<?php if(request()->routeIs('/auth/show-register.php')): ?>
    <div class="mb-3">
        <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
        <input type="password" class="form-control <?= isset($errors['password_confirm']) ? 'is-invalid' : '' ?>"
            id="password_confirm" name="password_confirm">
        <?php if(isset($errors['password_confirm'])): ?>
            <div class="invalid-feedback"><?= $errors['password_confirm'] ?></div>
        <?php endif; ?>
    </div>
<?php endif; ?>

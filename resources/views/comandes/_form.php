<div class="mb-3">
    <label for="client_id" class="form-label">Client</label>
    <select class="form-select <?= isset($errors['client_id']) ? 'is-invalid' : '' ?>"
        id="client_id" name="client_id">
        <option value="">-- Selecciona un client --</option>
        <?php foreach ($clients as $client): ?>
            <option value="<?= $client->id ?>"
                <?= $values['client_id'] == $client->id ? 'selected' : '' ?>>
                <?= htmlspecialchars($client->nom) ?> <?= htmlspecialchars($client->cognom) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (isset($errors['client_id'])): ?>
        <div class="invalid-feedback"><?= $errors['client_id'] ?></div>
    <?php endif; ?>
</div>

<div class="mb-3">
    <label for="total" class="form-label">Total</label>
    <div class="input-group">
        <input type="number" step="0.01" class="form-control <?= isset($errors['total']) ? 'is-invalid' : '' ?>"
            id="total" name="total" value="<?= $values['total'] ?>">
        <span class="input-group-text">€</span>
        <?php if (isset($errors['total'])): ?>
            <div class="invalid-feedback"><?= $errors['total'] ?></div>
        <?php endif; ?>
    </div>
</div>

<div class="mb-3">
    <label for="estat" class="form-label">Estat</label>
    <select class="form-select <?= isset($errors['estat']) ? 'is-invalid' : '' ?>"
        id="estat" name="estat">
        <option value="Pendent" <?= $values['estat'] == 'Pendent' ? 'selected' : '' ?>>Pendent</option>
        <option value="Enviat" <?= $values['estat'] == 'Enviat' ? 'selected' : '' ?>>Enviat</option>
        <option value="Completat" <?= $values['estat'] == 'Completat' ? 'selected' : '' ?>>Completat</option>
        <option value="Cancel·lat" <?= $values['estat'] == 'Cancel·lat' ? 'selected' : '' ?>>Cancel·lat</option>
    </select>
    <?php if (isset($errors['estat'])): ?>
        <div class="invalid-feedback"><?= $errors['estat'] ?></div>
    <?php endif; ?>
</div>

<div class="mb-3">
    <label for="direccio_enviament" class="form-label">Direcció d'enviament</label>
    <textarea class="form-control <?= isset($errors['direccio_enviament']) ? 'is-invalid' : '' ?>"
        id="direccio_enviament" name="direccio_enviament" rows="3"><?= $values['direccio_enviament'] ?></textarea>
    <?php if (isset($errors['direccio_enviament'])): ?>
        <div class="invalid-feedback"><?= $errors['direccio_enviament'] ?></div>
    <?php endif; ?>
</div>

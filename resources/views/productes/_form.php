<div class="mb-3">
    <label for="nom" class="form-label">Nom</label>
    <input type="text" class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>"
        id="nom" name="nom" value="<?= $values['nom'] ?>">
    <?php if (isset($errors['nom'])): ?>
        <div class="invalid-feedback"><?= $errors['nom'] ?></div>
    <?php endif; ?>
</div>

<div class="mb-3">
    <label for="categoria" class="form-label">Categoria</label>
    <select class="form-select <?= isset($errors['categoria']) ? 'is-invalid' : '' ?>"
        id="categoria" name="categoria">
        <option value="">-- Selecciona una categoria --</option>
        <option value="Plantes i llavors" <?= $values['categoria'] == 'Plantes i llavors' ? 'selected' : '' ?>>Plantes i llavors</option>
        <option value="Terra i adobs" <?= $values['categoria'] == 'Terra i adobs' ? 'selected' : '' ?>>Terra i adobs</option>
        <option value="Ferramentes" <?= $values['categoria'] == 'Ferramentes' ? 'selected' : '' ?>>Ferramentes</option>
    </select>
    <?php if (isset($errors['categoria'])): ?>
        <div class="invalid-feedback"><?= $errors['categoria'] ?></div>
    <?php endif; ?>
</div>

<div class="mb-3">
    <label for="preu" class="form-label">Preu</label>
    <div class="input-group">
        <input type="number" step="0.01" class="form-control <?= isset($errors['preu']) ? 'is-invalid' : '' ?>"
            id="preu" name="preu" value="<?= $values['preu'] ?>">
        <span class="input-group-text">€</span>
        <?php if (isset($errors['preu'])): ?>
            <div class="invalid-feedback"><?= $errors['preu'] ?></div>
        <?php endif; ?>
    </div>
</div>

<div class="mb-3">
    <label for="estoc" class="form-label">Estoc</label>
    <input type="number" class="form-control <?= isset($errors['estoc']) ? 'is-invalid' : '' ?>"
        id="estoc" name="estoc" value="<?= $values['estoc'] ?>">
    <?php if (isset($errors['estoc'])): ?>
        <div class="invalid-feedback"><?= $errors['estoc'] ?></div>
    <?php endif; ?>
</div>

<div class="mb-3">
    <label for="descripcio" class="form-label">Descripció</label>
    <textarea class="form-control <?= isset($errors['descripcio']) ? 'is-invalid' : '' ?>"
        id="descripcio" name="descripcio" rows="3"><?= $values['descripcio'] ?></textarea>
    <?php if (isset($errors['descripcio'])): ?>
        <div class="invalid-feedback"><?= $errors['descripcio'] ?></div>
    <?php endif; ?>
</div>

<div class="mb-3">
    <label for="detalls" class="form-label">Detalls</label>
    <textarea class="form-control <?= isset($errors['detalls']) ? 'is-invalid' : '' ?>"
        id="detalls" name="detalls" rows="3"><?= $values['detalls'] ?></textarea>
    <?php if (isset($errors['detalls'])): ?>
        <div class="invalid-feedback"><?= $errors['detalls'] ?></div>
    <?php endif; ?>
</div>

<div class="mb-3">
    <label for="imatge" class="form-label">Imatge</label>
    <select class="form-select <?= isset($errors['imatge']) ? 'is-invalid' : '' ?>"
        id="imatge" name="imatge">
        <option value="">-- Selecciona una imatge --</option>
        <optgroup label="Plantes i llavors">
            <option value="alfabrega.jpg" <?= $values['imatge'] == 'alfabrega.jpg' ? 'selected' : '' ?>>Alfabrega</option>
            <option value="romani.jpg" <?= $values['imatge'] == 'romani.jpg' ? 'selected' : '' ?>>Romaní</option>
            <option value="salvia.jpg" <?= $values['imatge'] == 'salvia.jpg' ? 'selected' : '' ?>>Sàlvia</option>
            <option value="timó.jpg" <?= $values['imatge'] == 'timó.jpg' ? 'selected' : '' ?>>Timó</option>
            <option value="encisam.jpg" <?= $values['imatge'] == 'encisam.jpg' ? 'selected' : '' ?>>Encisam</option>
            <option value="tomaca.jpg" <?= $values['imatge'] == 'tomaca.jpg' ? 'selected' : '' ?>>Tomaca</option>
            <option value="begonia.jpg" <?= $values['imatge'] == 'begonia.jpg' ? 'selected' : '' ?>>Begònia</option>
            <option value="petunia.jpg" <?= $values['imatge'] == 'petunia.jpg' ? 'selected' : '' ?>>Petúnia</option>
            <option value="gerani.jpg" <?= $values['imatge'] == 'gerani.jpg' ? 'selected' : '' ?>>Gerani</option>
        </optgroup>
        <optgroup label="Terra i adobs">
            <option value="adob_eco.jpg" <?= $values['imatge'] == 'adob_eco.jpg' ? 'selected' : '' ?>>Adob Ecològic</option>
            <option value="adob_floracio.jpg" <?= $values['imatge'] == 'adob_floracio.jpg' ? 'selected' : '' ?>>Adob per a Floració</option>
            <option value="compost_eco.jpg" <?= $values['imatge'] == 'compost_eco.jpg' ? 'selected' : '' ?>>Compost Ecològic</option>
            <option value="terra.jpg" <?= $values['imatge'] == 'terra.jpg' ? 'selected' : '' ?>>Terra</option>
            <option value="terra_universal.jpg" <?= $values['imatge'] == 'terra_universal.jpg' ? 'selected' : '' ?>>Terra Universal</option>
        </optgroup>
    </select>
    <?php if (isset($errors['imatge'])): ?>
        <div class="invalid-feedback"><?= $errors['imatge'] ?></div>
    <?php endif; ?>
    
    <?php if (!empty($values['imatge'])): ?>
        <div class="mt-2">
            <p>Imatge actual:</p>
            <img src="<?= imageUrl($values['imatge'], 150, 100) ?>" alt="Imatge actual" class="img-thumbnail" style="max-height: 100px;">
        </div>
    <?php endif; ?>
</div>

<?php 
// Add CSRF token field
use App\Http\Middlewares\Security\CsrfMiddleware;
echo CsrfMiddleware::tokenField();
?>

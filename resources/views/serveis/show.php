<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <?php if ($servei->imatge): ?>
                <img src="<?= BASE_URL . '/images/' . $servei->imatge ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($servei->nom) ?>">
            <?php else: ?>
                <div class="bg-light p-5 rounded text-center">
                    <span class="text-muted">Imatge no disponible</span>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h1 class="mb-2"><?= htmlspecialchars($servei->nom) ?></h1>
            <h4 class="text-primary mb-3"><?= number_format($servei->preu, 2) ?> €</h4>
            
            <div class="mb-3">
                <span class="badge bg-secondary"><?= htmlspecialchars($servei->categoria) ?></span>
            </div>
            
            <div class="mb-4">
                <h5>Descripció:</h5>
                <p><?= htmlspecialchars($servei->descripcio) ?></p>
            </div>
            
            <?php if ($servei->detalls): ?>
                <div class="mb-4">
                    <h5>Detalls:</h5>
                    <p><?= htmlspecialchars($servei->detalls) ?></p>
                </div>
            <?php endif; ?>
            
            <div class="d-grid gap-2">
                <a href="<?= BASE_URL ?>/contact/show-form.php" class="btn btn-success">Sol·licitar aquest servei</a>
                <a href="<?= previousUrl() ?>" class="btn btn-outline-secondary">← Tornar</a>
            </div>
        </div>
    </div>
</div>

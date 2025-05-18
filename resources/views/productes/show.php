<?php use App\Core\Auth; ?>

<div class="container mt-4">
    <!-- Mostrar mensajes de error y éxito si existen -->
    <?php include __DIR__ . '/../partials/message.php'; ?>

    <!-- Mostrar errores si existen -->
    <?php include __DIR__ . '/../partials/errors.php'; ?>

    <div class="row">
        <div class="col-md-6">
            <?php if ($producte->imatge): ?>
                <img src="<?= imageUrl($producte->imatge, 600, 400) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($producte->nom) ?>">
            <?php else: ?>
                <div class="bg-light p-5 rounded text-center">
                    <span class="text-muted">Imatge no disponible</span>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h1 class="mb-2"><?= htmlspecialchars($producte->nom) ?></h1>
            <h4 class="text-primary mb-3"><?= number_format($producte->preu, 2) ?> €</h4>
            
            <div class="mb-3">
                <span class="badge <?= $producte->estoc > 5 ? 'bg-success' : 'bg-warning' ?> mb-2">
                    Estoc: <?= htmlspecialchars($producte->estoc) ?>
                </span>
                <?php if ($producte->categoria): ?>
                    <span class="badge bg-secondary ms-2"><?= htmlspecialchars($producte->categoria) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <h5>Descripció:</h5>
                <p><?= htmlspecialchars($producte->descripcio) ?></p>
            </div>
            
            <?php if ($producte->detalls): ?>
                <div class="mb-4">
                    <h5>Detalls:</h5>
                    <p><?= htmlspecialchars($producte->detalls) ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (Auth::check()): ?>
                <form action="<?= BASE_URL . '/comandes/add-to-cart.php' ?>" method="POST" class="mb-3">
                    <input type="hidden" name="producte_id" value="<?= $producte->id ?>">
                    <div class="input-group mb-3">
                        <input type="number" name="quantitat" class="form-control" min="1" max="<?= $producte->estoc ?>" value="1">
                        <button type="submit" class="btn btn-primary">Afegir al carret</button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-info">Inicia sessió per comprar aquest producte.</div>
            <?php endif; ?>
            
            <a href="<?= previousUrl() ?>" class="btn btn-outline-secondary mt-3">← Tornar</a>
        </div>
    </div>
</div>

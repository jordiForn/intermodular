<div class="container mt-4">
    <h1 class="mb-4">Els nostres serveis</h1>
    
    <?php include __DIR__ . '/../partials/messages.php'; ?>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h3 class="card-title">Serveis de Jardineria</h3>
                    <p class="card-text">Descobreix els nostres serveis especialitzats en disseny, manteniment i cura de jardins.</p>
                    <a href="<?= BASE_URL ?>/serveis/jardins.php" class="btn btn-success">Veure serveis de jardineria</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h3 class="card-title">Serveis de Piscines</h3>
                    <p class="card-text">Explora els nostres serveis de manteniment, neteja i reparació de piscines.</p>
                    <a href="<?= BASE_URL ?>/serveis/piscines.php" class="btn btn-info">Veure serveis de piscines</a>
                </div>
            </div>
        </div>
    </div>
    
    <h2 class="mb-3">Tots els serveis</h2>
    
    <div class="row">
        <?php foreach ($serveis as $servei): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title"><?= htmlspecialchars($servei->nom) ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= number_format($servei->preu, 2) ?> €</h6>
                    </div>
                    <?php if ($servei->imatge): ?>
                        <img src="<?= BASE_URL . '/images/' . $servei->imatge ?>" class="card-img-top" alt="<?= htmlspecialchars($servei->nom) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <p class="card-text"><?= htmlspecialchars($servei->descripcio) ?></p>
                        <span class="badge bg-secondary"><?= htmlspecialchars($servei->categoria) ?></span>
                    </div>
                    <div class="card-footer">
                        <a href="<?= BASE_URL ?>/serveis/show.php?id=<?= $servei->id ?>" class="btn btn-primary btn-sm">Més informació</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

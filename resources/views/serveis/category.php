<div class="container mt-4">
    <h1 class="mb-4">
        <?php if ($categoria === 'jardins'): ?>
            Serveis de Jardineria
        <?php elseif ($categoria === 'piscines'): ?>
            Serveis de Piscines
        <?php else: ?>
            Serveis: <?= htmlspecialchars(ucfirst($categoria)) ?>
        <?php endif; ?>
    </h1>
    
    <?php include __DIR__ . '/../partials/message.php'; ?>
    
    <?php if (empty($serveis)): ?>
        <div class="alert alert-info">
            No hi ha serveis disponibles en aquesta categoria.
        </div>
    <?php else: ?>
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
                        </div>
                        <div class="card-footer">
                            <a href="<?= BASE_URL ?>/serveis/show.php?id=<?= $servei->id ?>" class="btn btn-primary btn-sm">Més informació</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="<?= BASE_URL ?>/serveis/index.php" class="btn btn-secondary">Tornar a tots els serveis</a>
    </div>
</div>

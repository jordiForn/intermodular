<div class="container mt-4">
    <h1>Les meves comandes</h1>

    <?php if (empty($comandes)): ?>
        <p>Encara no has fet cap comanda.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($comandes as $comanda): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Comanda #<?= $comanda->id ?></h5>
                            <span class="badge 
                                <?= $comanda->estat === 'Completat' ? 'bg-success' : 
                                   ($comanda->estat === 'Pendent' ? 'bg-warning' : 
                                   ($comanda->estat === 'Enviat' ? 'bg-info' : 'bg-danger')) ?>">
                                <?= htmlspecialchars($comanda->estat) ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><strong>Data:</strong> <?= htmlspecialchars($comanda->data_comanda) ?></p>
                            <p class="card-text"><strong>Total:</strong> <?= number_format($comanda->total, 2) ?> €</p>
                            <p class="card-text"><strong>Direcció:</strong> <?= htmlspecialchars($comanda->direccio_enviament) ?></p>
                        </div>
                        <div class="card-footer">
                            <a href="<?= BASE_URL ?>/comandes/show.php?id=<?= $comanda->id ?>" class="btn btn-info btn-sm">Veure detalls</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <a href="<?= HOME ?>" class="btn btn-secondary mt-4">Tornar a l'inici</a>
</div>

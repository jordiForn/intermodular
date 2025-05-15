<div class="container mt-5">
    <h2>Detalls de la Comanda</h2>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Comanda #<?= htmlspecialchars($comanda->id) ?></h4>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Data:</strong> <?= htmlspecialchars($comanda->data_comanda) ?></p>
                    <p><strong>Total:</strong> <?= number_format($comanda->total, 2) ?> €</p>
                    <p>
                        <strong>Estat:</strong> 
                        <span class="badge 
                            <?= $comanda->estat === 'Completat' ? 'bg-success' : 
                               ($comanda->estat === 'Pendent' ? 'bg-warning' : 
                               ($comanda->estat === 'Enviat' ? 'bg-info' : 'bg-danger')) ?>">
                            <?= htmlspecialchars($comanda->estat) ?>
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Client:</strong> 
                        <?php $client = $comanda->client; ?>
                        <?php if ($client): ?>
                            <a href="<?= BASE_URL ?>/clients/show.php?id=<?= $client->id ?>">
                                <?= htmlspecialchars($client->nom) ?> <?= htmlspecialchars($client->cognom) ?>
                            </a>
                        <?php else: ?>
                            <span class="text-muted">Client desconegut</span>
                        <?php endif; ?>
                    </p>
                    <p><strong>Direcció d'enviament:</strong> <?= htmlspecialchars($comanda->direccio_enviament) ?></p>
                </div>
            </div>
            
            <h5 class="mt-4">Productes de la comanda:</h5>
            <!-- Aquí se mostraría la lista de productos de la comanda -->
            <p class="text-muted">Funcionalitat pendent d'implementar</p>
        </div>
    </div>
    <div class="mt-3">
        <a href="<?= previousUrl() ?>" class="btn btn-outline-secondary mt-3">← Tornar</a>
        <?php if(Auth::check() && Auth::role() === 'admin'): ?>
            <a href="edit.php?id=<?= $comanda->id ?>" class="btn btn-warning mt-3">Editar</a>
        <?php endif; ?>
    </div>
</div>

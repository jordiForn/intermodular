<div class="container mt-5">
    <h2>Detalls del Client</h2>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?= htmlspecialchars($client->nom) ?> <?= htmlspecialchars($client->cognom) ?></h4>
            
            <?php $user = $client->user(); ?>
            <?php if ($user): ?>
                <p class="card-text"><strong>Nom d'usuari:</strong> <?= htmlspecialchars($user->username) ?></p>
                <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($user->email) ?></p>
                <p class="card-text"><strong>Rol:</strong> <?= htmlspecialchars($user->role) ?></p>
            <?php endif; ?>
            
            <p class="card-text"><strong>Telèfon:</strong> <?= htmlspecialchars($client->tlf) ?></p>
            
            <?php if ($client->getAdrecaCompleta()): ?>
                <p class="card-text"><strong>Adreça:</strong> <?= htmlspecialchars($client->getAdrecaCompleta()) ?></p>
            <?php endif; ?>
            
            <?php if ($client->consulta): ?>
                <p class="card-text"><strong>Consulta:</strong> <?= htmlspecialchars($client->consulta) ?></p>
            <?php endif; ?>
            
            <?php if ($client->missatge): ?>
                <p class="card-text"><strong>Missatge:</strong> <?= htmlspecialchars($client->missatge) ?></p>
            <?php endif; ?>
            
            <h5 class="mt-4">Comandes de <?= htmlspecialchars($client->nom) ?>:</h5>
            <?php $comandes = $client->comandes; ?>
            <?php if (empty($comandes)): ?>
                <p>Aquest client no té comandes.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Total</th>
                            <th>Estat</th>
                            <th>Acció</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comandes as $comanda): ?>
                            <tr>
                                <td><?= htmlspecialchars($comanda->id) ?></td>
                                <td><?= htmlspecialchars($comanda->data_comanda) ?></td>
                                <td><?= number_format($comanda->total, 2) ?> €</td>
                                <td><?= htmlspecialchars($comanda->estat) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/comandes/show.php?id=<?= $comanda->id ?>" class="btn btn-info btn-sm">Veure</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    <div class="mt-3">
        <a href="<?= previousUrl() ?>" class="btn btn-outline-secondary mt-3">← Tornar</a>
    </div>
</div>

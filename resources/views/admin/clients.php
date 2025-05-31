<div class="admin-clients">
    <h1 class="mb-4 text-highlight">Gestió de clients</h1>
    
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 text-highlight">Llistat de clients</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Cognom</th>
                            <th>Email</th>
                            <th>Telèfon</th>
                            <th>Consulta</th>
                            <th>Missatge</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($clients)): ?>
                            <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td><?= htmlspecialchars($client->nom ?? '') ?></td>
                                    <td><?= htmlspecialchars($client->cognom ?? '') ?></td>
                                    <td><?= htmlspecialchars($client->email ?? '') ?></td>
                                    <td><?= htmlspecialchars($client->tlf ?? '') ?></td>
                                    <td><?= htmlspecialchars($client->consulta ?? '') ?></td>
                                    <td><?= htmlspecialchars($client->missatge ?? '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No hi ha clients.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

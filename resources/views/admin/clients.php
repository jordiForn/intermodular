<div class="admin-clients">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-highlight">Gestió de clients</h1>
        <a href="/clients/create.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Afegir Client
        </a>
    </div>
    
    <!-- Success/Error Messages -->
    <?php if (session()->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= session()->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (session()->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= session()->getFlash('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0 text-highlight">
                <i class="fas fa-users"></i> Llistat de clients
                <span class="badge bg-primary ms-2"><?= count($clients) ?></span>
            </h5>
        </div>
        <div class="card-body">
            <?php if (!empty($clients)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Telèfon</th>
                                <th>Usuari</th>
                                <th>Rol</th>
                                <th>Accions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td><?= htmlspecialchars($client->id) ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($client->nom ?? '') ?></strong>
                                        <?php if ($client->cognom): ?>
                                            <?= htmlspecialchars($client->cognom) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="mailto:<?= htmlspecialchars($client->email ?? '') ?>">
                                            <?= htmlspecialchars($client->email ?? '') ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($client->tlf): ?>
                                            <a href="tel:<?= htmlspecialchars($client->tlf) ?>">
                                                <?= htmlspecialchars($client->tlf) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">No especificat</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <code><?= htmlspecialchars($client->nom_login ?? '') ?></code>
                                    </td>
                                    <td>
                                        <?php if ($client->rol === 1): ?>
                                            <span class="badge bg-danger">Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Client</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/clients/show.php?id=<?= $client->id ?>" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Veure detalls">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/clients/edit.php?id=<?= $client->id ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger delete-client" 
                                                    data-client-id="<?= $client->id ?>"
                                                    data-client-name="<?= htmlspecialchars($client->nom . ' ' . $client->cognom) ?>"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hi ha clients registrats</h5>
                    <p class="text-muted">Comença afegint el primer client al sistema.</p>
                    <a href="/clients/create.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Afegir primer client
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Confirmar eliminació
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Estàs segur que vols eliminar el client <strong id="clientName"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Atenció:</strong> Aquesta acció no es pot desfer. S'eliminaran totes les dades associades al client.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?= session()->get('csrf_token') ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete button clicks
    document.querySelectorAll('.delete-client').forEach(button => {
        button.addEventListener('click', function() {
            const clientId = this.dataset.clientId;
            const clientName = this.dataset.clientName;
            
            // Update modal content
            document.getElementById('clientName').textContent = clientName;
            document.getElementById('deleteForm').action = '/clients/destroy.php?id=' + clientId;
            
            // Show modal
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(alert => {
            if (alert.classList.contains('show')) {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            }
        });
    }, 5000);
});
</script>

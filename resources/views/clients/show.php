<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user"></i> Detalls del client
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-user-cog"></i> Informació d'usuari</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">ID</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($client->id) ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Nom d'usuari</label>
                                        <p class="form-control-plaintext">
                                            <code><?= htmlspecialchars($client->nom_login ?? 'No especificat') ?></code>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Email</label>
                                        <p class="form-control-plaintext">
                                            <a href="mailto:<?= htmlspecialchars($client->email ?? '') ?>">
                                                <?= htmlspecialchars($client->email ?? 'No especificat') ?>
                                            </a>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Rol</label>
                                        <p class="form-control-plaintext">
                                            <?php if ($client->rol === 1): ?>
                                                <span class="badge bg-danger">Administrador</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Client</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-user"></i> Dades personals</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Nom complet</label>
                                        <p class="form-control-plaintext">
                                            <strong><?= htmlspecialchars($client->nom ?? '') ?></strong>
                                            <?php if ($client->cognom): ?>
                                                <?= htmlspecialchars($client->cognom) ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Telèfon</label>
                                        <p class="form-control-plaintext">
                                            <?php if ($client->tlf): ?>
                                                <a href="tel:<?= htmlspecialchars($client->tlf) ?>">
                                                    <?= htmlspecialchars($client->tlf) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">No especificat</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($client->consulta || $client->missatge): ?>
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-comment"></i> Informació adicional</h6>
                            </div>
                            <div class="card-body">
                                <?php if ($client->consulta): ?>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Tipus de consulta</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-primary"><?= htmlspecialchars(ucfirst($client->consulta)) ?></span>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($client->missatge): ?>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Missatge</label>
                                        <div class="border rounded p-3 bg-light">
                                            <?= nl2br(htmlspecialchars($client->missatge)) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/admin/clients.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Tornar al llistat
                        </a>
                        <div>
                            <a href="/clients/edit.php?id=<?= $client->id ?>" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <button type="button" 
                                    class="btn btn-danger delete-client" 
                                    data-client-id="<?= $client->id ?>"
                                    data-client-name="<?= htmlspecialchars($client->nom . ' ' . $client->cognom) ?>">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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
                    <strong>Atenció:</strong> Aquesta acció no es pot desfer.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?php 
                    use App\Http\Middlewares\Security\CsrfMiddleware;
                    echo CsrfMiddleware::tokenField();
                    ?>
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
    // Handle delete button click
    document.querySelector('.delete-client')?.addEventListener('click', function() {
        const clientId = this.dataset.clientId;
        const clientName = this.dataset.clientName;
        
        // Update modal content
        document.getElementById('clientName').textContent = clientName;
        document.getElementById('deleteForm').action = '/clients/destroy.php?id=' + clientId;
        
        // Show modal
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    });
});
</script>

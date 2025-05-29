<?php
// Admin Clients Management View
?>

<div class="admin-clients">
    <h1 class="mb-4 text-highlight">Gestió de Clients</h1>
    
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 text-highlight">Llistat de Clients</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Telèfon</th>
                            <th>Adreça</th>
                            <th>Comandes</th>
                            <th>Accions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?= $client->id ?></td>
                                <td><?= htmlspecialchars($client->nom) ?></td>
                                <td><?= htmlspecialchars($client->email) ?></td>
                                <td><?= htmlspecialchars($client->telefon) ?></td>
                                <td><?= htmlspecialchars($client->adreca) ?></td>
                                <td>
                                    <span class="badge bg-success"><?= $client->orderCount ?? 0 ?></span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= BASE_URL ?>/clients/edit.php?id=<?= $client->id ?>" 
                                           class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-primary view-client" 
                                                data-client-id="<?= $client->id ?>" data-bs-toggle="tooltip" 
                                                title="Veure detalls">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-client" 
                                                data-client-id="<?= $client->id ?>" data-bs-toggle="tooltip" 
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
        </div>
    </div>
</div>

<!-- Client Details Modal -->
<div class="modal fade" id="clientDetailsModal" tabindex="-1" aria-labelledby="clientDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-highlight text-white">
                <h5 class="modal-title" id="clientDetailsModalLabel">Detalls del Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="clientDetailsContent">
                <!-- Client details will be loaded here -->
                <div class="text-center">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Carregant...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tancar</button>
                <a href="#" id="editClientLink" class="btn btn-success">Editar Client</a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteClientModal" tabindex="-1" aria-labelledby="deleteClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteClientModalLabel">Confirmar Eliminació</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Estàs segur que vols eliminar aquest client? Aquesta acció no es pot desfer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
                <a href="#" id="confirmDeleteClient" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle view client button clicks
    document.querySelectorAll('.view-client').forEach(button => {
        button.addEventListener('click', function() {
            const clientId = this.dataset.clientId;
            const clientDetailsContent = document.getElementById('clientDetailsContent');
            const editClientLink = document.getElementById('editClientLink');
            
            // Update the edit link
            editClientLink.href = `${BASE_URL}/clients/edit.php?id=${clientId}`;
            
            // Show the modal
            const clientModal = new bootstrap.Modal(document.getElementById('clientDetailsModal'));
            clientModal.show();
            
            // Load client details via AJAX
            fetch(`${BASE_URL}/clients/get-details.php?id=${clientId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        clientDetailsContent.innerHTML = data.html;
                    } else {
                        clientDetailsContent.innerHTML = '<div class="alert alert-danger">Error en carregar les dades del client.</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    clientDetailsContent.innerHTML = '<div class="alert alert-danger">Error en la comunicació amb el servidor.</div>';
                });
        });
    });
    
    // Handle delete button clicks
    document.querySelectorAll('.delete-client').forEach(button => {
        button.addEventListener('click', function() {
            const clientId = this.dataset.clientId;
            const deleteLink = document.getElementById('confirmDeleteClient');
            deleteLink.href = `${BASE_URL}/clients/destroy.php?id=${clientId}`;
            
            // Show the modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteClientModal'));
            deleteModal.show();
        });
    });
});
</script>

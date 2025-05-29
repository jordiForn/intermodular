<?php
// Admin Orders Management View
?>

<div class="admin-orders">
    <h1 class="mb-4 text-highlight">Gestió de Comandes</h1>
    
    <div class="card mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-highlight">Llistat de Comandes</h5>
            <div>
                <select class="form-select form-select-sm d-inline-block me-2" style="width: auto;" id="orderStatusFilter">
                    <option value="all">Tots els estats</option>
                    <option value="pending">Pendents</option>
                    <option value="completed">Completades</option>
                    <option value="cancelled">Cancel·lades</option>
                </select>
                <button class="btn btn-sm btn-outline-success" id="exportOrders">
                    <i class="fas fa-file-export"></i> Exportar
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Data</th>
                            <th>Import</th>
                            <th>Estat</th>
                            <th>Productes</th>
                            <th>Accions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <?php 
                                $client = \App\Models\Client::find($order->client_id);
                                $clientName = $client ? $client->nom : 'Client desconegut';
                                
                                // Determine status class
                                $statusClass = 'bg-success';
                                $statusText = 'Completada';
                                
                                if ($order->estat === 'pendent') {
                                    $statusClass = 'bg-warning text-dark';
                                    $statusText = 'Pendent';
                                } elseif ($order->estat === 'cancel·lada') {
                                    $statusClass = 'bg-danger';
                                    $statusText = 'Cancel·lada';
                                }
                            ?>
                            <tr>
                                <td><?= $order->id ?></td>
                                <td><?= htmlspecialchars($clientName) ?></td>
                                <td><?= date('d/m/Y', strtotime($order->data_comanda)) ?></td>
                                <td><?= number_format($order->import_total, 2) ?> €</td>
                                <td>
                                    <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary view-products" 
                                            data-order-id="<?= $order->id ?>">
                                        <i class="fas fa-box"></i> <?= $order->productCount ?? 0 ?>
                                    </button>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-success update-status" 
                                                data-order-id="<?= $order->id ?>" data-bs-toggle="tooltip" 
                                                title="Actualitzar estat">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary view-order" 
                                                data-order-id="<?= $order->id ?>" data-bs-toggle="tooltip" 
                                                title="Veure detalls">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-order" 
                                                data-order-id="<?= $order->id ?>" data-bs-toggle="tooltip" 
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

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-highlight text-white">
                <h5 class="modal-title" id="orderDetailsModalLabel">Detalls de la Comanda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <!-- Order details will be loaded here -->
                <div class="text-center">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Carregant...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tancar</button>
                <button type="button" class="btn btn-success" id="printOrder">Imprimir</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="updateStatusModalLabel">Actualitzar Estat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm">
                    <input type="hidden" id="orderIdInput" name="orderId">
                    <div class="mb-3">
                        <label for="statusSelect" class="form-label">Nou Estat</label>
                        <select class="form-select" id="statusSelect" name="status">
                            <option value="completada">Completada</option>
                            <option value="pendent">Pendent</option>
                            <option value="cancel·lada">Cancel·lada</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
                <button type="button" class="btn btn-success" id="confirmUpdateStatus">Actualitzar</button>
            </div>
        </div>
    </div>
</div>

<!-- Products Modal -->
<div class="modal fade" id="orderProductsModal" tabindex="-1" aria-labelledby="orderProductsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-highlight" id="orderProductsModalLabel">Productes de la Comanda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="orderProductsContent">
                <!-- Products will be loaded here -->
                <div class="text-center">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Carregant...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tancar</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteOrderModalLabel">Confirmar Eliminació</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Estàs segur que vols eliminar aquesta comanda? Aquesta acció no es pot desfer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
                <a href="#" id="confirmDeleteOrder" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle view order button clicks
    document.querySelectorAll('.view-order').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const orderDetailsContent = document.getElementById('orderDetailsContent');
            
            // Show the modal
            const orderModal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
            orderModal.show();
            
            // Load order details via AJAX
            fetch(`${BASE_URL}/comandes/get-details.php?id=${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        orderDetailsContent.innerHTML = data.html;
                    } else {
                        orderDetailsContent.innerHTML = '<div class="alert alert-danger">Error en carregar les dades de la comanda.</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    orderDetailsContent.innerHTML = '<div class="alert alert-danger">Error en la comunicació amb el servidor.</div>';
                });
        });
    });
    
    // Handle update status button clicks
    document.querySelectorAll('.update-status').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            document.getElementById('orderIdInput').value = orderId;
            
            // Show the modal
            const statusModal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
            statusModal.show();
        });
    });
    
    // Handle view products button clicks
    document.querySelectorAll('.view-products').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const productsContent = document.getElementById('orderProductsContent');
            
            // Show the modal
            const productsModal = new bootstrap.Modal(document.getElementById('orderProductsModal'));
            productsModal.show();
            
            // Load products via AJAX
            fetch(`${BASE_URL}/comandes/get-products.php?id=${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        productsContent.innerHTML = data.html;
                    } else {
                        productsContent.innerHTML = '<div class="alert alert-danger">Error en carregar els productes de la comanda.</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    productsContent.innerHTML = '<div class="alert alert-danger">Error en la comunicació amb el servidor.</div>';
                });
        });
    });
    
    // Handle delete button clicks
    document.querySelectorAll('.delete-order').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const deleteLink = document.getElementById('confirmDeleteOrder');
            deleteLink.href = `${BASE_URL}/comandes/destroy.php?id=${orderId}`;
            
            // Show the modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteOrderModal'));
            deleteModal.show();
        });
    });
    
    // Handle status filter changes
    document.getElementById('orderStatusFilter').addEventListener('change', function() {
        const status = this.value;
        // This would typically filter the table or reload the page with a status filter
        alert(`Filtrant per estat: ${status}`);
    });
    
    // Handle export button clicks
    document.getElementById('exportOrders').addEventListener('click', function() {
        alert('Exportació de comandes en desenvolupament.');
    });
    
    // Handle print button clicks
    document.getElementById('printOrder').addEventListener('click', function() {
        const content = document.getElementById('orderDetailsContent').innerHTML;
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Detalls de la Comanda</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { padding: 20px; }
                        @media print {
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h1 class="mb-4">Detalls de la Comanda</h1>
                        ${content}
                        <div class="mt-4 no-print">
                            <button class="btn btn-primary" onclick="window.print()">Imprimir</button>
                            <button class="btn btn-secondary" onclick="window.close()">Tancar</button>
                        </div>
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
    });
    
    // Handle confirm update status button clicks
    document.getElementById('confirmUpdateStatus').addEventListener('click', function() {
        const orderId = document.getElementById('orderIdInput').value;
        const status = document.getElementById('statusSelect').value;
        
        // This would typically make an AJAX request to update the status
        alert(`Actualitzant estat de la comanda ${orderId} a ${status}`);
        
        // Close the modal
        const statusModal = bootstrap.Modal.getInstance(document.getElementById('updateStatusModal'));
        statusModal.hide();
    });
});
</script>
